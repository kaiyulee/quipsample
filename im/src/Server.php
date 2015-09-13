<?php
namespace WebIM;
use Swoole;
use Swoole\Filter;

class Server extends Swoole\Protocol\CometServer
{
    /**
     * @var Store\File;
     */
    protected $store;
    protected $ustore;
    protected $users;

    const MESSAGE_MAX_LEN     = 1024; //单条消息不得超过1K
    const WORKER_HISTORY_ID   = 0;

    function __construct($config = array())
    {
        //将配置写入config.js
        $config_js = <<<HTML
var webim = {
    'server' : '{$config['server']['url']}'
}
HTML;
        file_put_contents(WEBPATH . '/assets/js/config.js', $config_js);

        //检测日志目录是否存在
        $log_dir = dirname($config['webim']['log_file']);
        if (!is_dir($log_dir)) {
            mkdir($log_dir, 0777, true);
        }
        if (!empty($config['webim']['log_file'])) {
            $logger = new Swoole\Log\FileLog($config['webim']['log_file']);
        }
        else {
            $logger = new Swoole\Log\EchoLog;
        }
        $this->setLogger($logger);   //Logger

        /**
         * 使用文件或redis存储聊天信息
         */
        $this->setStore(new \WebIM\Store\File($config['webim']['data_dir']));
        $this->setUStore(new \WebIM\Store\Redis());

        $this->origin = $config['server']['origin'];
        parent::__construct($config);
    }

    function setStore($store)
    {
        $this->store = $store;
    }

    function setUStore($store)
    {
        $this->ustore = $store;
    }
    /**
     * 下线时，通知所有人
     */
    function onExit($client_id)
    {
        $userInfo = $this->store->getUser($client_id);

        if ($userInfo) {
            $resMsg = array(
                'cmd' => 'offline',
                'fd' => $client_id,
                'from' => 0,
                'channal' => 0,
                'data' => $userInfo['name'] . "下线了",
            );

            $this->store->logout($client_id);

            //将下线消息发送给所有人
            $this->broadcastJson($client_id, $resMsg);
        }

        $this->log("onOffline: " . $client_id);
    }

    function onTask($serv, $task_id, $from_id, $data)
    {
        $req = unserialize($data);
        if ($req)
        {
            switch($req['cmd'])
            {
                case 'getHistory':
                    $history = array('cmd'=> 'getHistory', 'history' => $this->store->getHistory());
                    if ($this->isCometClient($req['fd'])) {
                        return $req['fd'].json_encode($history);
                    }
                    //WebSocket客户端可以task中直接发送
                    else {
                        $this->sendJson(intval($req['fd']), $history);
                    }
                    break;
                case 'addHistory':
                    if (empty($req['msg'])) {
                        $req['msg'] = '';
                    }
                    $this->store->addHistory($req['msg'], $req['user']);
                    break;
                default:
                    break;
            }
        }
    }

    function onFinish($serv, $task_id, $data)
    {
        $this->send(substr($data, 0, 32), substr($data, 32));
    }

    /**
     * 获取历史聊天记录
     */
    function cmd_getHistory($client_id, $msg)
    {
        $task['fd'] = $client_id;
        $task['cmd'] = 'getHistory';
        $task['offset'] = '0,100';
        //在task worker中会直接发送给客户端
        $this->getSwooleServer()->task(serialize($task), self::WORKER_HISTORY_ID);
    }

    /**
     * 登录
     * @param $client_id
     * @param $msg
     */
    function cmd_login($client_id, $msg)
    {
        $uid = $msg['uid'];
        $user = $this->ustore->getUser($uid);

        //回复给登录用户
        $resMsg = array(
            'cmd' => 'login',
            'fd' => $client_id,
            'uid' => $uid,
            'name' => $user['name'],
            'avatar' => $user['avatar'],
        );

        //把会话存起来
        $this->users[$client_id] = $resMsg;

        $this->store->login($client_id, $resMsg);
        $this->sendJson($client_id, $resMsg);

        //广播给其它在线用户
        $resMsg['cmd'] = '';
        //将上线消息发送给所有人
        $this->broadcastJson($client_id, $resMsg);
        //用户登录消息
        $loginMsg = array(
            'cmd' => 'fromMsg',
            'from' => 0,
            'channal' => 0,
            'data' => $user['name'] . "上线了",
        );
        $this->broadcastJson($client_id, $loginMsg);
    }

    /**
     * 发送信息请求
     */
    function cmd_message($client_id, $msg)
    {
        $resMsg = $msg;
        $uid = $msg['from'];
        $user = $this->ustore->getUser($uid);
        $resMsg['cmd']      = 'fromMsg';
        $resMsg['name']     = $user['name'];
        $resMsg['avatar']   = $user['avatar'];

        if (strlen($msg['data']) > self::MESSAGE_MAX_LEN) {
            $this->sendErrorMessage($client_id, 102, 'message max length is '.self::MESSAGE_MAX_LEN);
            return;
        }
        
        //表示群发
        if ($msg['channal'] == 0)
        {
            $this->broadcastJson($client_id, $resMsg);
            $this->getSwooleServer()->task(serialize(array(
                'cmd'       => 'addHistory',
                'msg'       => $msg,
                'fd'        => $client_id,
                'user'      => $user,
            )), self::WORKER_HISTORY_ID);
        }
    }

    /**
     * 接收到消息时
     * @see WSProtocol::onMessage()
     */
    function onMessage($client_id, $ws)
    {
        $this->log("onMessage #$client_id: " . $ws['message']);
        $msg = json_decode($ws['message'], true);
        if (empty($msg['cmd'])) {
            $this->sendErrorMessage($client, 101, "invalid command");
            return;
        }
        $func = 'cmd_'.$msg['cmd'];

        if (method_exists($this, $func)) {
            $this->$func($client_id, $msg);
        } else {
            $this->sendErrorMessage($client_id, 102, "command $func no support.");
            return;
        }
    }

    function sendErrorMessage($client_id, $code, $msg)
    {
        $this->sendJson($client_id, array('cmd' => 'error', 'code' => $code, 'msg' => $msg));
    }

    function sendJson($client_id, $array)
    {
        $msg = json_encode($array);
        if ($this->send($client_id, $msg) === false)
        {
            $this->close($client_id);
        }
    }

    function broadcastJson($sesion_id, $array)
    {
        $msg = json_encode($array);
        $this->broadcast($sesion_id, $msg);
    }

    function broadcast($current_session_id, $msg)
    {
        foreach ($this->users as $uid => $name)
        {
            if ($current_session_id != $uid)
            {
                $this->send($uid, $msg);
            }
        }
    }
}


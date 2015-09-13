<?php

class RedisConn
{
    protected $redis;
    static $prefix = "webim_";
    function __construct($host = '127.0.0.1', $port = 6379, $timeout = 0.0)
    {
        $redis = new \redis;
        $redis->connect($host, $port, $timeout);
        $this->redis = $redis;
    }

    function setUser($uid, $data)
    {
     
        $key = self::$prefix . 'user_' . $uid;
        $this->redis->set($key, serialize($data));
    }

}

$redis = new RedisConn();

$data = array(
    1 => array(
        'name' => '纪刚',
        'avatar' => 'http://tp2.sinaimg.cn/1658423893/50/5668673952/1'
    ),
    2 => array(
        'name' => '邹强',
        'avatar' => 'http://jigang.mojichina.com/assets/img/avatar/avatar.jpg'
    ),
    3 => array(
        'name' => '赵春野',
        'avatar' => 'http://jigang.mojichina.com/assets/img/avatar/avatar.jpg'
    ),
    4 => array(
        'name' => '杜秀华',
        'avatar' => 'http://jigang.mojichina.com/assets/img/avatar/avatar.jpg'
    )

);

foreach ($data as $k => $v) {

    $redis->setUser($k, $v);
}


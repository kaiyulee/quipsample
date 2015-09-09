<?php
namespace App\Controller;

use Swoole;

class Directory extends Swoole\Controller
{
    public $is_ajax = true;

    function __construct($swoole)
    {
        parent::__construct($swoole);
        $this->model = model('Directory');
    }

    public function all()
    {
        // 获取用户所有文件夹
        $user = $_SESSION['user'];

        if (empty($user)) {
            return ['code' => 4, 'data' => '', 'msg' => 'who are you!'];
        }

        $dirs = $this->db->query("SELECT * FROM directory WHERE uid = {$user['id']}")->fetchall();

        //var_dump($dirs);
        //
        //Swoole::$php->http->finish();
        $resort = [];

        if (!empty($dirs)) {
            foreach ($dirs as $key => $val) {
                $id = $val['id'];
                $resort[$id] = [];
            }

            foreach ($dirs as $dir) {
                $resort[$dir['pid']][] = $dir;
            }
        }

        return ['code' => 0, 'data' => $resort];
    }

    public function add()
    {
        // 创建文件夹
        $pid = $_POST['pid'];
        $name = $_POST['name'];
        $user = $_SESSION['user'];

        if (empty($user)) {
            return ['code' => 4, 'data' => '', 'msg' => 'who are you!'];
        }

        $row    = [
            'pid'           => $pid,
            'uid'           => $user['id'],
            'foldername'    => $name,
            'create_time'   => time(),
            'update_time'   => time()
        ];

        $res = $this->model->put($row);

        if (false === $res) {
            return ['code' => 1, 'data' => 'false'];
        } else {
            return ['code' => 0, 'data' => 'true'];
        }
    }

    public function del()
    {
        // 删除文件夹
        $dir_id = $_REQUEST['dir_id'];

        $user = $_SESSION['user'];

        if (empty($user)) {
            return ['code' => 4, 'data' => '', 'msg' => 'who are you!'];
        }

        $where = 'uid = ' . $user['id'];

        $res = $this->model->del($dir_id, $where);

        if (false === $res) {
            return ['code' => 1, 'data' => 'false'];
        } else {
            return ['code' => 0, 'data' => 'true'];
        }

    }

    public function update()
    {
        // 重命名
        $user = $_SESSION['user'];

        if (empty($user)) {
            return ['code' => 4, 'data' => '', 'msg' => 'who are you!'];
        }

        $name = $_POST['new_name'];
        $dir_id = $_POST['dir_id'];

        $row = [
            'foldername' => $name,
            'update_time' => time()
        ];

        $where = "uid = {$user['id']} AND id = {$dir_id}";

        $res = $this->model->set($dir_id, $row, $where);

        if (false === $res) {
            return ['code' => 1, 'data' => 'false'];
        } else {
            return ['code' => 0, 'data' => 'true'];
        }
    }
}
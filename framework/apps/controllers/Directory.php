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

    public function desktop()
    {
        $user = $_SESSION['user'];

        if (empty($user)) {
            return ['code' => 4, 'data' => '', 'msg' => 'who are you?'];
        }

        $files = [];
        // 分两步查询
        // 1, root 下的文件夹
        //$sql = "SELECT * FROM `directory` WHERE uid={$user['id']} AND pid = {$user['root_dir_id']}";
        //$sql = "SELECT * FROM `directory` WHERE pid = 0";
        //$files['dirs'] = $this->db->query($sql)->fetchall();

        // 2, root 下的文档
        //$sql = "SELECT * FROM `document` WHERE uid={$user['id']} AND dirid = {$user['root_dir_id']}";
        $sql = "SELECT * FROM `document` WHERE dirid = 0";

        $files['docs'] = $this->db->query($sql)->fetchall();

        return ['code' => 0, 'data' => $files, 'msg' => 'success'];
    }

    /**
     * both folders and docs under a specific folder
     * @return array
     */
    public function files()
    {
        $pid = $_REQUEST['dir_id'];

        if (empty($pid)) {
            return ['code' => 5, 'data' => '', 'msg' => 'param error'];
        }

        // 获取用户所有文件夹
        $user = $_SESSION['user'];

        if (empty($user)) {
            return ['code' => 4, 'data' => '', 'msg' => 'who are you?'];
        }

        $files = [];
        // 分两步查询
        // 1, root 下的文件夹
        $sql = "SELECT * FROM `directory` WHERE uid={$user['id']} AND pid = {$pid}";
        $files['dirs'] = $this->db->query($sql)->fetchall();

        // 2, root 下的文档
        $sql = "SELECT * FROM `document` WHERE uid={$user['id']} AND dirid = {$pid}";

        $files['docs'] = $this->db->query($sql)->fetchall();

        return ['code' => 0, 'data' => $files];
    }

    public function add()
    {
        // 创建文件夹
        $pid = $_REQUEST['pid'];
        $name = $_REQUEST['name'];

        if (empty($pid) or empty($name)) {
            return ['code' => 5, 'data' => '', 'msg' => 'param error'];
        }

        $user = $_SESSION['user'];

        if (empty($user)) {
            return ['code' => 4, 'data' => '', 'msg' => 'who are you?'];
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

        if (empty($dir_id)) {
            return ['code' => 5, 'data' => '', 'msg' => 'param error'];
        }

        $user = $_SESSION['user'];

        if (empty($user)) {
            return ['code' => 4, 'data' => '', 'msg' => 'who are you?'];
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
            return ['code' => 4, 'data' => '', 'msg' => 'who are you?'];
        }

        $name = $_REQUEST['new_name'];
        $dir_id = $_REQUEST['dir_id'];

        if (empty($dir_id) or empty($name)) {
            return ['code' => 5, 'data' => '', 'msg' => 'param error'];
        }

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
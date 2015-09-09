<?php
/**
 * @author Kron
 * @since 15/9/8 18:29
 */

namespace App\Controller;

use Swoole;
class Document extends Swoole\Controller
{
    public $is_ajax = true;

    function __construct($swoole)
    {
        parent::__construct($swoole);

        $this->model = model('Document');
    }

    public function add()
    {
        $dir_id = $_REQUEST['dir_id'];
        $doc_name = $_REQUEST['doc_name'];

        if (empty($dir_id) or empty($doc_name)) {
            return ['code' => 5, 'data' => '', 'msg' => 'param error'];
        }

        $user = $_SESSION['user'];

        if (empty($user)) {
            return ['code' => 4, 'data' => '', 'msg' => 'who are you!'];
        }

        $row = [
            'uid' => $user['id'],
            'dirid' => $dir_id,
            'docname' => $doc_name,
            'create_time' => time(),
            'update_time' => time()
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
        $doc_id = $_REQUEST['doc_id'];

        $user = $_SESSION['user'];

        if (empty($user)) {
            return ['code' => 4, 'data' => '', 'msg' => 'who are you!'];
        }

        $where = 'uid = ' . $user['id'];

        $res = $this->model->del($doc_id, $where);

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

        $name = $_REQUEST['new_name'];
        $doc_id = $_REQUEST['doc_id'];

        $row = [
            'docname' => $name,
            'update_time' => time()
        ];

        $where = 'uid = ' . $user['id'];

        $res = $this->model->set($doc_id, $row, $where);

        if (false === $res) {
            return ['code' => 1, 'data' => 'false'];
        } else {
            return ['code' => 0, 'data' => 'true'];
        }
    }

    /**
     * 获取指定文件夹下的文档
     * @return array
     */
    public function docs()
    {
        $dir_id = $_REQUEST['dir_id'];

        if (empty($dir_id)) {
            return ['code' => 5, 'data' => '', 'msg' => 'param error'];
        }

        $user = $_SESSION['user'];

        if (empty($user)) {
            return ['code' => 4, 'data' => '', 'msg' => 'who are you!'];
        }

        $sql = 'SELECT * FROM document AS doc ';
        $sql .= 'LEFT JOIN directory AS dir ON dir.id = doc.dirid ';
        $sql .= 'WHERE doc.uid = ' . $user['id'];
        $sql .= ' AND dir.id = ' . $dir_id . ' ORDER BY doc.update_time DESC';

        $docs = $this->db->query($sql)->fetchall();

        return ['code' => 0, 'data' => $docs];
    }

    public function all()
    {
        $user = $_SESSION['user'];

        if (empty($user)) {
            return ['code' => 5, 'data' => '', 'msg' => 'who are you!'];
        }

        $sql = 'SELECT * FROM document AS doc ';
        $sql .= 'LEFT JOIN directory AS dir ON dir.id = doc.dirid ';
        $sql .= 'WHERE doc.uid = ' . $user['id'];
        $sql .= ' ORDER BY doc.update_time DESC';

        $docs = $this->db->query($sql)->fetchall();

        return ['code' => 0, 'data' => $docs];
    }
}
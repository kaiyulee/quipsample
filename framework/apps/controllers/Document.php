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
        //$dir_id = $_REQUEST['dir_id'];
        $dir_id = 0;
        $doc_name = $_REQUEST['doc_name'];

        if (!isset($dir_id) or empty($doc_name)) {
            return ['code' => 5, 'data' => '', 'msg' => 'param error'];
        }

        $user = $_SESSION['user'];

        if (empty($user)) {
            return ['code' => 4, 'data' => '', 'msg' => 'who are you?'];
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

    public function force_del()
    {
        $doc_id = $_REQUEST['doc_id'];

        $user = $_SESSION['user'];

        if (empty($user)) {
            return ['code' => 4, 'data' => '', 'msg' => 'who are you?'];
        }

        //$where = 'uid = ' . $user['id'];
        // 正处于锁状态的不能删
        $where = 'lock = 0';

        $res = $this->model->del($doc_id, $where);

        if (false === $res) {
            return ['code' => 1, 'data' => '', 'msg' => 'fail'];
        } else {
            return ['code' => 0, 'data' => '', 'msg' => 'success'];
        }

    }

    public function soft_del()
    {
        $doc_id = $_REQUEST['doc_id'];

        $user = $_SESSION['user'];

        if (empty($user)) {
            return ['code' => 4, 'data' => '', 'msg' => 'who are you?'];
        }

        $row = [
            'status' => 1,
            'update_time' => time()
        ];

        //$where = 'uid = ' . $user['id'];
        // 正处于锁状态的不能删
        $where = 'lock = 0';

        $res = $this->model->set($doc_id, $row, $where);

        if (false === $res) {
            return ['code' => 1, 'data' => '', 'msg' => 'fail'];
        } else {
            return ['code' => 0, 'data' => '', 'msg' => 'success'];
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
        $doc_id = $_REQUEST['doc_id'];

        //判断当前状态，有锁则不能编辑
        $is_locked = self::check_status($doc_id);

        if ($is_locked) {
            return ['code' => 1024, 'data' => '', 'msg' => 'section locked'];
        }

        // 如果没锁，先加个锁
        self::lock($doc_id);

        $row = [
            'docname' => $name,
            'update_time' => time()
        ];

        //$where = 'uid = ' . $user['id'];
        $where = '';

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
            return ['code' => 4, 'data' => '', 'msg' => 'who are you?'];
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
            return ['code' => 5, 'data' => '', 'msg' => 'who are you?'];
        }

        $sql = 'SELECT * FROM document AS doc ';
        $sql .= 'LEFT JOIN directory AS dir ON dir.id = doc.dirid ';
        $sql .= 'WHERE doc.uid = ' . $user['id'];
        $sql .= ' ORDER BY doc.update_time DESC';

        $docs = $this->db->query($sql)->fetchall();

        return ['code' => 0, 'data' => $docs];
    }

    public function show()
    {
        $doc_id = $_REQUEST['doc_id'];

        if (empty($doc_id)) {
            return ['code' => 5, 'data' => '', 'msg' => 'param error'];
        }

        $user = $_SESSION['user'];

        if (empty($user)) {
            return ['code' => 4, 'data' => '', 'msg' => 'who are you!'];
        }

        $sql = 'SELECT `content` FROM `section` ';
        $sql .= 'LEFT JOIN `document` ON document.id = section.docid ';
        //$sql .= 'WHERE section.uid = ' . $user['id'] . ' AND docid = ' . $doc_id;
        $sql .= 'WHERE docid = ' . $doc_id;
        $sql .= ' ORDER BY section.id ASC';

        $docs = $this->db->query($sql)->fetchall();

        return ['code' => 0, 'data' => $docs, 'msg' => 'success'];
    }

    public static function check_status($doc_id)
    {
        $res = Swoole::getInstance()->db->query('SELECT `lock` FROM `document` WHERE `id` = ' . $doc_id)->fetch();

        $locked = empty($res['lock']) ? false : true;

        return $locked;
    }

    private static function lock($doc_id)
    {
        Swoole::getInstance()->db->query('UPDATE `document` SET `lock` = 1 WHERE `id` = ' . $doc_id);
    }

    private static function unlock($doc_id)
    {
        Swoole::getInstance()->db->query('UPDATE `document` SET `lock` = 0 WHERE `id` = ' . $doc_id);
    }
}
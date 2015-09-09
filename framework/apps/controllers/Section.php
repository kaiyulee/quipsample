<?php
/**
 * @author Kron
 * @since 15/9/9 15:44
 */

namespace App\Controller;

use Swoole;
class Section extends Swoole\Controller
{
    public $is_ajax = true;

    function __construct($swoole)
    {
        parent::__construct($swoole);

        $this->model = model('Section');
    }

    public function add()
    {
        $doc_id = $_REQUEST['doc_id'];
        $content = $_REQUEST['content'];

        if (empty($doc_id) or empty($content)) {
            return ['code' => 5, 'data' => '', 'msg' => 'param error'];
        }

        $user = $_SESSION['user'];

        if (empty($user)) {
            return ['code' => 4, 'data' => '', 'msg' => 'who are you!'];
        }

        $row = [
            'uid' => $user['id'],
            'docid' => $doc_id,
            'content' => $content,
            'create_time' => time(),
            'update_time' => time(),
            'lock' => 0
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
        $section_id = $_REQUEST['section_id'];

        $user = $_SESSION['user'];

        if (empty($user)) {
            return ['code' => 4, 'data' => '', 'msg' => 'who are you!'];
        }

        $where = 'uid = ' . $user['id'] . ' AND lock = 0';

        $res = $this->model->del($section_id, $where);

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

        $content = $_REQUEST['content'];
        $section_id = $_REQUEST['section_id'];
        //TODO 判断当前状态，有锁则不能编辑
        $is_locked = self::check_status($section_id);

        if ($is_locked) {
            return ['code' => 1024, 'data' => '', 'msg' => 'section locked'];
        }

        // 如果没锁，先加个锁
        self::add_lock($section_id);

        //Swoole::$php->http->finish();

        $row = [
            'content' => $content,
            'lock' => 0,
            'update_time' => time()
        ];

        $where = 'uid = ' . $user['id'];

        $res = $this->model->set($section_id, $row, $where);

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
    public function sec()
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

    public static function check_status($section_id)
    {
        $locked = false;

        $res = Swoole::getInstance()->db->query('SELECT `lock` FROM `section` WHERE `id` = ' . $section_id)->fetch();

        $locked = empty($res['lock']) ? false : true;

        return $locked;
    }

    private static function add_lock($section_id)
    {
        Swoole::getInstance()->db->query('UPDATE `section` SET `lock` = 1 WHERE `id` = ' . $section_id);
    }

}
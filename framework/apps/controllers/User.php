<?php
/**
 * @author Kron
 * @since 15/9/8 17:47
 */

namespace App\Controller;

use Swoole;

class User extends Swoole\Controller
{
    //public $is_ajax = true;

    public function __construct($swoole)
    {
        parent::__construct($swoole);
    }

    public function login()
    {
        //var_dump(Swoole::$php->session);
        $this->tpl->display('user/login.php');
    }

    public function logout()
    {
    }
}
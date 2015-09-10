<?php
namespace App\Controller;
use Swoole;
use App;

class Api extends Swoole\Controller
{
    public $is_ajax = true;

    function __construct($swoole)
    {
        parent::__construct($swoole);
        //Swoole::$php->session->start();
        //Swoole\Auth::login_require();
    }

    public function user()
    {
        $auth       = empty($_REQUEST['auth']) ? '' : $_REQUEST['auth'];

        if (!empty($auth)) return App\User::auth($auth);
    }
}





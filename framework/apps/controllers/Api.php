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
        //$id         = empty($_REQUEST['id']) ? '' : $_REQUEST['id'];
        $id         = empty($_POST['id']) ? '' : $_POST['id'];
        $auth       = empty($_POST['auth']) ? '' : $_POST['auth'];
        
        if (!empty($auth)) return App\User::auth($auth);
    }
    
}





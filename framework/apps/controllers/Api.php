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

        $this->session->start();
    }

    public function user()
    {
        $auth = empty($_REQUEST['auth']) ? '' : $_REQUEST['auth'];

        if (!empty($auth)) return App\User::auth($auth);
    }

    public function getUserInfo()
    {
        return $_SESSION['user'];
    }
}





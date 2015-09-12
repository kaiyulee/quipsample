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
        Swoole::$php->session->start();
        //Swoole\Auth::login_require();
    }

    public function user()
    {
        $email      = empty($_POST['username']) ? '' : $_POST['username'];
        $password   = empty($_POST['password']) ? '' : $_POST['password'];

        //$email = 'gang.ji1@moji.com';
        if (empty($email)) return ['code' => 1];
        if (empty($password)) return ['code' => 2];

        $db = Model('User')->get($email, 'email');
        $user = $db->get();

        if (empty($user)) return ['code' => 3];

        if ($user['password'] !== md5($password)) return ['code' => 4]; 

        unset($user['password']);
        
        $_SESSION['user'] = $user;

        return ['code' => 0, 'data'=>$user];
    }
}





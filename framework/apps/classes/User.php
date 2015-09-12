<?php
namespace App;
use Swoole;

class User
{
    static function auth($auth)
    {
        list($email, $password) = [$auth['email'],$auth['password']];
        
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

    /**
     * for SOA Server
     * @return array
     */
    static function test1()
    {
        return array('file' => __FILE__, 'method' => __METHOD__);
    }
}

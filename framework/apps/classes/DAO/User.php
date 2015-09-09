<?php
namespace App\DAO;

/**
 * Class User
 * example: $user = new App\DAO\User(1);  $user->get();
 * @package App\DAO
 */
class User
{


    function get($id)
    {
        return model('User')->get($id);
    }

    static function all()
    {
        return model('User')->all();
    }
}

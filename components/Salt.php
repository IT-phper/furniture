<?php
namespace app\components;

class Salt {

    public static function generateSalt($password)
    {
        $salt = mt_rand( 100000, 999999 );
        $hash = md5($password);
        $salt_hash = md5($salt.$hash);
        return $result = ['salt' => strval($salt), 'hash' => $salt_hash];
    }

    public static function verifySalt($password, $salt)
    {
        $salt = $salt;
        $hash = md5($password);
        $salt_hash = md5($salt.$hash);
        return $salt_hash;
    }
}

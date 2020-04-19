<?php
namespace Clas;
class GeneratePass{
    public static function pass(string $prefix){
        return (uniqid($prefix, true));
    }
    public static function sha(string $string){
        //return(hash("Argon2", $string));
        return(password_hash($string, PASSWORD_ARGON2I, ['memory_cost' => 2048, 'time_cost' => 4, 'threads' => 3]));
    }
    public static function verify(string $string, $hash){
        return (password_verify($string, $hash));
    }
}
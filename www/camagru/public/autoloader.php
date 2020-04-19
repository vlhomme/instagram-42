<?php
class Autoloader {


    static function register() {
        spl_autoload_register(array(__CLASS__, 'autoload'));
    }

    static function autoload($class) {
        $class = str_replace('\\', '/', $class);
        $class = substr($class, 5);
        require '../class/' . $class . '.php';
    }
} 
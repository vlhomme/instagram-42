<?php

namespace Clas;

class Error_message {
    public function display ($message){
        require '../template/error_message.html';
        echo $message;
        require '../template/error_message2.html';
    }
}
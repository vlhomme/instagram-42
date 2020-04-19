<?php

namespace Clas\Table;
use Clas\Database\BasicQuery;

class ProfilePicture extends Picture {
    public $table = 'profile_picture';
    public function __construct()
    {
        $this->table = 'profile_picture';
    }
}
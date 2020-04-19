<?php

namespace Clas\Database;

    class JsCommentExport
    {
        public $image;
        public $pseudo;
        public $content;
        public $date;
        public $likes;
        public $liked;
        public $isLiked;
        public $id;

        public function __construct($image_path, $pseudo, $content, $date, $likes, $id, $liked, $isLiked)
        { 
            $this->image = $image_path;
            $this->pseudo = $pseudo;
            $this->content = $content;
            $this->date = $date;
            $this->likes = $likes;
            $this->liked = $liked;
            $this->isLiked = $isLiked;
            $this->id = $id;
        }
    }

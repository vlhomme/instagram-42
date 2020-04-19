<?php

use Clas\Table\Picture;

$truc = $_GET['next_gallery'];

$truc = 21 * $truc;

$picture =  new Picture();
$posts = $picture->GetAll($db, "ORDER BY `created` DESC LIMIT 21 OFFSET " . $truc);

if (empty ($posts)){
    echo (json_encode('end'));
} else {
    $data = json_encode([
        'posts' => $posts
    ]);
    
    echo $data;
}
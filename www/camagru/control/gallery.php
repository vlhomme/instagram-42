<?php

use Clas\Table\Picture;

$picture =  new Picture();
$posts = $picture->GetAll($db, "ORDER BY `created` DESC LIMIT 21");

//il nous faut l'id de la derniere pour relancer une requete de la suite

$data = json_encode([
    'posts' => $posts
]);

echo "<div aria-hidden='true' style='display: none;' class='js_user_data'>$data</div>";
<?php
use Clas\Table\Picture;
use Clas\Database\BasicQuery;

$id = $_GET['post_id'];
$picture = new Picture();
$post = $picture->GetAll($db, "WHERE id = $id")[0];

$pdo = new BasicQuery($db);
$useless_variable = $_SESSION["pseudo"];
$id_connected_user = intval($pdo->execute('SELECT id FROM `users` WHERE `pseudo` = ?', [$useless_variable])->fetch(\PDO::FETCH_ASSOC)['id']);

$array_of_liked = (array)json_decode($post->liked);
$bool_isliked = array_search($id_connected_user, $array_of_liked) !== false;

$response = json_encode([
    'post' => $post,
    'is_liked' => $bool_isliked
]);
echo $response;
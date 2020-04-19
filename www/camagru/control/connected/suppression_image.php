<?php

use Clas\Database\BasicQuery;

$post_id = $_GET['suppress_post'];

//if foto appartient a utilisateur

$useless_variable = htmlspecialchars($_SESSION["pseudo"]);
// dump($useless_variable);
$pdo = new BasicQuery($db);
$id_connected_user = intval($pdo->execute('SELECT id FROM `users` WHERE `pseudo` = ?', [$useless_variable])->fetch(\PDO::FETCH_ASSOC)['id']);
// dump($id_connected_user);

$pdo = new BasicQuery($db);
$id_user = $pdo->execute('SELECT `id_user` from `pictures` where id = ?', [$post_id])->fetch(\PDO::FETCH_ASSOC)['id_user'];

if (intval($id_connected_user) === intval($id_user)) {
    //suppress comment
$pdo = new BasicQuery($db);
$pdo->execute('DELETE FROM `comment` WHERE `id_picture` = ?', [$post_id]);

//suppress post
$pdo = new BasicQuery($db);
$pdo->execute('DELETE FROM `pictures` WHERE `id` = ?', [$post_id]);

echo 'OK';
} else {
    echo 'NOT YOUR FOTO';
}


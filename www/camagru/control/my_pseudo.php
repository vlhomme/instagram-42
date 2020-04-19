<?php 

use Clas\Database\BasicQuery;

$pdo = new BasicQuery($db);
$pseudo = htmlspecialchars($_SESSION["pseudo"]);
// dump($pseudo);
$id = intval($pdo->execute('SELECT id FROM `users` WHERE `pseudo` = ?', [$pseudo])->fetch(\PDO::FETCH_ASSOC)['id']);
// dump($id);
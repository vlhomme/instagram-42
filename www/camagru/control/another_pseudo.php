<?php 

use Clas\Database\BasicQuery;

//check first if pseudo exists then send error if not

$pseudo =  htmlspecialchars($_GET['another_pseudo']);
// dump($pseudo);
$pdo = new BasicQuery($db);
$id = intval($pdo->execute('SELECT id FROM `users` WHERE `pseudo` = ?', [$pseudo])->fetch(\PDO::FETCH_ASSOC)['id']);
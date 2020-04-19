<?php

use Clas\Database\BasicQuery;

$pdo = new BasicQuery($db);
$result = $pdo->execute('SELECT `mail` FROM `users` WHERE `id` = ?', [$_GET['get_mail']])->fetch(\PDO::FETCH_ASSOC);
echo ($result['mail']) ;
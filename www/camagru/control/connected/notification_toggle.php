<?php 
use Clas\Database\BasicQuery;
//on va utiliser l'onglet admin. si admin est sur 0 les notifs sont activees si admin est sur 1 les notifs sont desactivees

if ($_GET['notification_toggle'] === 'nowoff') {

    $pseudo = $_SESSION['pseudo'];
    $pdo = new BasicQuery($db);
    $ret = $pdo->execute('UPDATE `users` SET `admin` = 1 WHERE `users`.`pseudo` = ?; ', [$pseudo]);
    if ($ret !== null) {
        echo 'OK';
    }

} elseif ($_GET['notification_toggle'] === 'nowon') {

    $pseudo = $_SESSION['pseudo'];
    $pdo = new BasicQuery($db);
    $ret = $pdo->execute('UPDATE `users` SET `admin` = 0 WHERE `users`.`pseudo` = ?; ', [$pseudo]);
    if ($ret !== null) {
        echo 'OK';
    }
};
<?php

use Clas\Database\BasicQuery;
use Clas\Table\User;

$response = 'OK';

if (isset($_GET['verif_mail'])) {

    $mail = $_GET['verif_mail'];
    if (empty($mail)) {
        $response = 'Vous devez rentrer une valeur dans le champ mail.';
    } else {
        $pdo = new BasicQuery($db);
        $response = json_encode($pdo->execute("SELECT * FROM `users` WHERE mail = ?", [$mail])->fetch(\PDO::FETCH_ASSOC));
        if ($response === 'false') {
            $response = 'L\'email que vous avez rentré ne correspond a aucun utilisateur dans notre base de donnée';
        } else {
            $response = 'OK';
            //envoyer l'email a l'utilisateur
            $user = new User();
            $user->SendRecovery($db, $mail);
        }
    }
} elseif (isset($_POST['password_change'])) {

    if (!$_POST['password_change']){
        $response = 'Vous devez rentrer une valeur pour votre nouveau mot de passe.';
    } else {

        $id = $_POST['id'];
        $pass = $_POST['password_change'];

        $user = new User();
        $user->Import($db, $id);
        $user->ChangePass($db, $pass);
        if ($user->ErrorMessage !== null) {
            $response = $user->ErrorMessage;
        }
    }
}

echo $response;
<?php

use Clas\Table\User;

$error_message = 'OK';
$pseudo = $_POST['pseudo'];
$pass = $_POST['pass'];

if (empty($pseudo))
$error_message = 'Vous avez oublié d\'entrer votre pseudo';
if (empty($pass))
$error_message = 'Vous avez oublié d\'entrer votre mot de passe';
if (!empty($pseudo) && !empty($pass)) {
    $user = new User();
    if ($user->Check($db, $pseudo, $pass) !== true) {
        $error_message = $user->ErrorMessage;
    } else {
        $_SESSION["pseudo"]= $pseudo;
    }
}

echo $error_message;

// $jsonstr = json_encode([
//     'pseudo' => $pseudo,
//     'pass' => $pass
// ]);

// echo $jsonstr;
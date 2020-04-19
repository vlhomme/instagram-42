<?php

use Clas\Table\User;

$error_message = 'OK';
$pseudo = $_POST['pseudo'];
$prenom = $_POST['prenom'];
$nom = $_POST['nom'];
$mail = $_POST['mail'];
$pass = $_POST['pass'];

if (empty($pseudo) || empty($pass) || empty($prenom) || empty($nom) || empty($mail))
$error_message = 'Tous les champs sont obligatoires pour créer un compte';
else {
    $user = new User();
    $user->Add($db, $pseudo, $prenom, $nom, $mail, $pass);
    if ($user->ErrorMessage === null){
        $user->SendValidationMail();
        $error_message = 'Bravo ! Votre compte a été créer avec succès. Veuillez consulter vos email.';
    } else {
        $error_message = $user->ErrorMessage;
    }
}

echo $error_message;
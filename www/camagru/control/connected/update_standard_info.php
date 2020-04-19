<?php

//utilisateur connecte = 

use Clas\Table\User;

require ('../control/my_pseudo.php');

//il faut aussi verifier si l'utilisateur change bien ses donnees a lui et pas celles d'un autre

if (isset($_SESSION['pseudo'])) {
    //  echo 'chatte';
    // echo $id;
    // $id = 6;
    //  echo $_GET['update_standard_info'];

    // function JSON_sanitize($str)= 

    // $input = iconv('UTF-8', 'UTF-8//IGNORE', utf8_encode($_GET['update_standard_info']));

    $new_info_user = json_decode($_GET['update_standard_info']);
    // dump($new_info_user);
    if ($new_info_user === null) {
        echo 'Veuillez changer vos valeurs, elles semblent être mal formatées où constituer une menace';
        // echo json_last_error();
        die();
    }
    $new_info_user = (array)$new_info_user;
    // dump($new_info_user);
    $user = new User();
    $user->Import($db, $id);
    $user->Update($db, $new_info_user);
    if ($user->ErrorMessage === null) {
        $_SESSION['pseudo'] = htmlspecialchars_decode($user->pseudo);
        // dump($user->pseudo);
        // dump($_SESSION['pseudo']);
        echo 'OK';
    } else {
        echo $user->ErrorMessage;
    }
    $new_bio = $new_info_user['bio'];
    // dump($new_bio);
    $user->AddBio($db, $new_bio);
} else {
    echo 'user not connected';
}
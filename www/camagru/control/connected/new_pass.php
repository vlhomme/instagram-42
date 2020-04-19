<?php 

// echo 'it works';

use Clas\Table\User;

require ('../control/my_pseudo.php');

if (!isset($_POST['new_pass2']) || empty($_POST['new_pass2']) || empty($_POST['new_pass1'])) {
    if (empty($_POST['new_pass2']) || empty($_POST['new_pass1'])){
        echo 'Vous ne pouvez pas rentrer des valeurs nulles';
    } else {
        echo 'Vous devez remplir les deux champs avec une valeur identique';
    }
} elseif ($_POST['new_pass2'] !== $_POST['new_pass1']){
    echo 'Vous devez remplir les deux champs avec une valeur identique';
} else {
    $nouveau_mot_de_passe = $_POST['new_pass2'];

    $user = new User();
    $user->Import($db, $id);
    $user->ChangePass($db, $nouveau_mot_de_passe);
    if ($user->ErrorMessage !== null) {
        echo $user->ErrorMessage;
    } else {
        echo 'OK';
    }
}
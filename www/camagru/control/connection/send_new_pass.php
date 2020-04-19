<?php

use Clas\Table\User;

$user = new User();
if ($user->ConfirmLinkRecovery($db, $_GET['id'], $_GET['pass']) !== true){
    $str = "Veuillez vérifier que vous aillez bien utilisé le lien du dernier email envoyé, il semblerait que votre lien soit expiré ou corrompu";
    echo "<div class=\"js-check-validation\" style=\"display: none;\">$str</div>";
} else {
    $str = 'change_pass_form';
    $id = $_GET['id'];
    echo "<div class=\"js-check-validation\" style=\"display: none;\">$str</div><div class=\"js-id-user\" style=\"display: none;\">$id</div>";
}
require '../template/test.html';
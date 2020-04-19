<?php
    if (preg_match("/^id=\d+\&valid=verif.{23}$/", $_SERVER['QUERY_STRING'])){
        require '../control/connection/check_validation.php';
    } elseif (preg_match("/^id=\d+\&pass=forgot.{23}$/", $_SERVER['QUERY_STRING'])) {
        require "../control/connection/send_new_pass.php";
    } elseif (isset($_POST['pseudo']) && !isset($_POST['nom'])) {
        require '../control/connection/connection.php';
    } elseif (isset($_GET['gallery']) && !empty($_GET['gallery'])) {
        require_once '../control/gallery.php';
        require '../template/gallery.html';
        // require '../control/connection/connection.php';
    } elseif (isset($_GET['next_gallery']) && !empty($_GET['next_gallery'])) {
        require_once '../control/next_gallery.php';
    } elseif (isset($_POST['pseudo'])) {
        require '../control/connection/registration.php';
    } elseif (isset($_GET['verif_mail']) || isset($_POST['password_change'])){
        require '../control/connection/verif_mail.php';
    } else {
        require '../template/test.html';
    } 

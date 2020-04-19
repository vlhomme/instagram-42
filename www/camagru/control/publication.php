<?php 
// 1 . publish the picture
// 2 . unset session['montage']
// 3 . send 'OK' to front if everything went fine

use Clas\Table\Picture;

//1
//get picture and put it into img/publication
$picture_path = $_GET['publish_picture'];
$lolilol = uniqid();
$new_path = 'img/publication' . $lolilol . substr($picture_path, -4);
copy($picture_path, $new_path);
//get id
require_once '../control/my_pseudo.php';
$picture = new Picture();
$picture->Add($db, $new_path, $id);

//2
unset($_SESSION['montage']);

//3
echo 'OK';
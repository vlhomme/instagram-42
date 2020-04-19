<?php 

use Clas\Upload;

$lefichier = $_FILES['fichier'];
$nom = (Upload::verify_and_upload($lefichier));

echo $nom;

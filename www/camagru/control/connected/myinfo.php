<?php

use Clas\Table\User;
use Clas\Table\Picture;
use Clas\Table\ProfilePicture;

require_once '../control/my_pseudo.php';
$user = new User();
$user->Import($db, $id);
$picture = new Picture();
$posts = $picture->GetAll($db, "WHERE id_user = $id");
$prof_picture = new ProfilePicture();
$profile_pic = $prof_picture->GetAll($db, "WHERE id_user = $id")[0];

$pseudo = $user->pseudo;
$prenom = $user->prenom;
$nom = $user->nom;
$bio = $user->bio;
$user_data = json_encode([
    'id' => $id,
    'pseudo' => $pseudo,
    'prenom' => $prenom,
    'nom' => $nom,
    'profile_pic' => $profile_pic,
    'posts' => $posts,
    'bio' => $bio
]);

// echo "<div aria-hidden='true' style='display: none;' class='js_user_data'>$user_data</div>";
echo $user_data;

// require '../template/feed.html';
<?php

use Clas\Database\BasicQuery;
use Clas\Table\User;
use Clas\Table\Picture;
use Clas\Table\ProfilePicture;

// $pseudo = $_SESSION["pseudo"];
// $pdo = new BasicQuery($db);
// $pseudo = $_SESSION["pseudo"];
// $id = intval($pdo->execute('SELECT id FROM `users` WHERE `pseudo` = ?', [$pseudo])->fetch(\PDO::FETCH_ASSOC)['id']);

// $id = rand(4,39);
// $id = 37;

$user = new User();
$user->Import($db, $id);
$picture = new Picture();
$posts = $picture->GetAll($db, "WHERE id_user = $id ORDER BY `created` DESC");
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

// echo json_encode($user_data);
// echo "<div aria-hidden='true' style='display: none;' class='js_user_data'>$user_data</div>";

require '../template/feed.html';
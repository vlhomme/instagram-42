<?php
use Clas\Table\User;
use Clas\Table\Picture;
use Clas\Table\ProfilePicture;
use Clas\Database\BasicQuery;

// $pseudo = $_SESSION["pseudo"];
// $pdo = new BasicQuery($db);
// $pseudo = $_SESSION["pseudo"];
// $id = intval($pdo->execute('SELECT id FROM `users` WHERE `pseudo` = ?', [$pseudo])->fetch(\PDO::FETCH_ASSOC)['id']);

// $id = rand(4,39);
// $id = 37;

if ($_GET['public_info'] === 'z') {

// dump($id);
// echo $id;

$user = new User();
$user->Import($db, $id);
$picture = new Picture();
$posts = $picture->GetAll($db, "WHERE id_user = $id ORDER BY `created` DESC");
$prof_picture = new ProfilePicture();
$profile_pic = $prof_picture->GetAll($db, "WHERE id_user = $id")[0];

// $pseudo = addslashes(htmlentities($user->pseudo));
// $prenom = addslashes(htmlentities($user->prenom));
// $nom = addslashes(htmlentities($user->nom));
// $bio = addslashes(htmlentities($user->bio));
$pseudo = $user->pseudo;
$prenom = $user->prenom;
$nom = $user->nom;
$bio = $user->bio;
//on va utiliser l'onglet admin. si admin est sur 0 les notifs sont activees si admin est sur 1 les notifs sont desactivees
$notif = $user->admin;
$user_data = json_encode([
    'id' => $id,
    'pseudo' => $pseudo,
    'prenom' => $prenom,
    'nom' => $nom,
    'profile_pic' => $profile_pic,
    'posts' => $posts,
    'bio' => $bio,
    'notif' => $notif
]);

echo json_encode($user_data);

} else {

//il fo dabor remplacer %3b par ;
// dump($_GET['public_info']);
$tmp = $_GET['public_info'];
// dump($tmp);

$tmp = (preg_replace('/<;/', '<', $tmp));
$tmp = (preg_replace('/>;/', '>', $tmp));

$pseudo = htmlspecialchars($tmp);
// dump($pseudo);
$pdo = new BasicQuery($db);
$id = intval($pdo->execute('SELECT id FROM `users` WHERE `pseudo` = ?', [$pseudo])->fetch(\PDO::FETCH_ASSOC)['id']);

$user = new User();
$user->Import($db, $id);
$picture = new Picture();
$posts = $picture->GetAll($db, "WHERE id_user = $id ORDER BY `created` DESC");
$prof_picture = new ProfilePicture();
$profile_pic = $prof_picture->GetAll($db, "WHERE id_user = $id")[0];

// $pseudo = addslashes(htmlentities($user->pseudo));
// $prenom = addslashes(htmlentities($user->prenom));
// $nom = addslashes(htmlentities($user->nom));
// $bio = addslashes(htmlentities($user->bio));
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

if ($id === 0 && $pseudo === null) {
    echo 'User doesn\'t exist';
    die();
}

echo json_encode($user_data);

}
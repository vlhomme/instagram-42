<?php
use Clas\Database\BasicQuery;
use Clas\Table\User;
use Clas\GeneratePass;

$pdo = new BasicQuery($db);
$picture_table_query = "CREATE TABLE IF NOT EXISTS profile_picture (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `id_user` INT NOT NULL,
    `path` VARCHAR (300) NOT NULL,
    `likes` INT,
    `liked` VARCHAR (1200) NOT NULL,
    `comment` INT,
    `created` DATETIME NOT NULL)";
$ret = $pdo->execute($picture_table_query, []);

$pdo = new BasicQuery($db);
$user_table_query = "CREATE TABLE IF NOT EXISTS users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    pseudo VARCHAR(250) NOT NULL,
    prenom VARCHAR(250) NOT NULL,
    nom VARCHAR(250) NOT NULL,
    mail VARCHAR(250) NOT NULL,
    pass VARCHAR(100) NOT NULL,
    verificationPass VARCHAR(100) NOT NULL,
    created DATETIME NOT NULL,
    updated DATETIME NOT NULL,
    `admin` BOOLEAN,
    `suspended` BOOLEAN,
    bio VARCHAR(250))";
$pdo->execute($user_table_query, []);

$thais = new User();
$thais->Add($db, 'ShitFixer', 'Thais', 'Avelino', 'tavelino@student.42.fr', '12&-@bUI34', 'https://scontent-cdg2-1.cdninstagram.com/vp/8428ee4f599900351006fcc425f58681/5E0C7168/t51.2885-19/s150x150/65819432_2439215076099560_228563268841504768_n.jpg?_nc_ht=scontent-cdg2-1.cdninstagram.com');
echo $thais->GetErrors();
$naceur = new User();
$naceur->Add($db, 'VL', 'naceur', 'Lhomme', 'vlhomme@student.42.fr', '12&-@bUI34', '/img/profile_pic_vi.jpg');
$test = new User();
$test->Add($db, 'ValideTest', 'Test', 'Test-Test', 'tatikecaj@daymailonline.com', '12&-@bUI34');
var_dump($thais);
var_dump($naceur);
var_dump($test);
echo "default base users added in newly created users table<br>";
// $test->SendValidationMail();
// echo "mail probably send";
// $test = GeneratePass::pass("prout");
// $sha = GeneratePass::sha($test);
// dump(GeneratePass::verify($test, $sha));


$poirier = file_get_contents('../config/prenoms.json');
$prenoms = json_decode($poirier);

$i = 0;
while ($i < 35) {
    $ran = rand(0,99);
    $sexe = rand(0,1);
    $prenom = $prenoms[$sexe][$ran];
    $prenom = ucfirst(strtolower($prenom));
    $raw_data = file_get_contents("https://fr.wikipedia.org/w/api.php?action=opensearch&search=$prenom");
    $php_data = json_decode($raw_data);
    $ran2 = rand(1, count ($php_data[1]) - 1);
    $pseudo = $php_data[1][$ran2];
    $description = $php_data[2][$ran2];

    if (substr($pseudo, 0, strpos($pseudo, ' ')) === '') {
        $firstname = $pseudo;
        $nom = 'bot';
    } else {
        $firstname = substr($pseudo, 0, strpos($pseudo, ' '));
        $nom = preg_replace("/[^A-Za-z- ]+/", "-", User::RemoveAccemt(substr($pseudo, strpos($pseudo, ' ') + 1)));
    }
    $mail = "bot" . $i . "@gmail.com";
    $mdp = '1234567Pp-';
    
    $bot = new User();
    $bot->add($db, $pseudo, $firstname, $nom, $mail, $mdp);
    $bot->AddBio($db, $description);
    var_dump($bot);
    sleep (1);
    $i++;
}

$pdo = new BasicQuery($db);
$pdo->execute('UPDATE `users` set `suspended` = ?', [0]);
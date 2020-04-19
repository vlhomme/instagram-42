<?php
use Clas\Database\BasicQuery;
use Clas\Table\Picture;
use Clas\Date_Operation;

$pdo = new BasicQuery($db);
$picture_table_query = "CREATE TABLE IF NOT EXISTS pictures (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `id_user` INT NOT NULL,
    `path` VARCHAR (300) NOT NULL,
    `likes` INT,
    `liked` VARCHAR (1200) NOT NULL,
    `comment` INT,
    `created` DATETIME NOT NULL)";
$ret = $pdo->execute($picture_table_query, []);
//dump($ret);
?>

<style>
    img {
        max-height: 100%;
        max-width: 100%;
        object-fit: contain;
        margin: 5px;
    }
    img:hover{
        background-color: white;
    }
    .square {
    height: 150px;
    width: 150px;
}
</style>
<?php

$pommier = file_get_contents('../config/picture-first-test.json');
$biblio_image = json_decode($pommier);
$pdo = new BasicQuery($db);
$query = "SELECT * FROM users WHERE id > 3";
$ret = $pdo->execute($query, [])->fetchAll(\PDO::FETCH_ASSOC);
foreach ($ret as $e){
    for ($i = 0; $i < 10; $i++) {

        $now = new DateTime("now -3 year");
        $threeYearsAgo = new DateTime("now -4 year");
        $random_date = Date_Operation::randomDateInRange($threeYearsAgo, $now);
        $timestamp_for_comment = $random_date->getTimestamp();
        $date_for_picture = date('Y-m-d G:i:s', $timestamp_for_comment);

        $ran = rand(0, count($biblio_image) - 1);
        preg_match('/src=".+"/', $biblio_image[$ran], $matches);
        $path = substr($matches[0], 5);
        substr_replace($path ,"", -1);
        $picture = new Picture();
        $picture->Add($db, $path, $e['id'], $date_for_picture);
        echo $picture->ToHTML('square');
    }
}

$lesphotosdenaceur = glob('../public/img/publication*');
if ($lesphotosdenaceur === false || $lesphotosdenaceur === []) {
    echo 'error for naceur photo';
    die();
} else {
    foreach($lesphotosdenaceur as $photoDenaceur) {

        $now = new DateTime("now -3 year");
        $threeYearsAgo = new DateTime("now -4 year");
        $random_date = Date_Operation::randomDateInRange($threeYearsAgo, $now);
        $timestamp_for_comment = $random_date->getTimestamp();
        $date_for_picture = date('Y-m-d G:i:s', $timestamp_for_comment);

        $picture = new Picture();
        $picture->Add($db, strstr($photoDenaceur, '/img'), 2, $date_for_picture);
        echo $picture->ToHTML('square');
    }
}

// $pommier = file_get_contents('../config/picture-first-test.json');
// $biblio_image = json_decode($pommier);
// $ran = rand(0, count($biblio_image) - 1);

// preg_match('/src=".+"/', $biblio_image[$ran], $matches);
// dump($matches[0]);
// echo '<img ' . $matches[0] . '/>';

//DISPLAY ERROR MESSAGE
//Error_message::display($user->ErrorMessage);
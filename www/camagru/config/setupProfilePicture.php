<?php

use Clas\Database\BasicQuery;
use Clas\Table\ProfilePicture;

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


$ppic = new ProfilePicture();
$ppic->Add($db, 'https://scontent-cdg2-1.cdninstagram.com/vp/8428ee4f599900351006fcc425f58681/5E0C7168/t51.2885-19/s150x150/65819432_2439215076099560_228563268841504768_n.jpg?_nc_ht=scontent-cdg2-1.cdninstagram.com', 1);
$ppic = new ProfilePicture();
$ppic->Add($db, '/img/profile_pic_vi.jpg', 2);
$ppic = new ProfilePicture();
$ppic->Add($db, '/img/ico/user.png', 3);

$pdo = new BasicQuery($db);
$query = "SELECT * FROM users WHERE id > 3";
$ret = $pdo->execute($query, [])->fetchAll(\PDO::FETCH_ASSOC);
foreach ($ret as $e){
    $ppic = new ProfilePicture();
    $ppic->Add($db, '/img/ico/user.png', $e['id']);
}
<?php
use Clas\Database\BasicQuery;
use Clas\Table\Picture;
use Clas\Table\Comment;
use Clas\Date_Operation;

// $comment_table_query = "drop table comment IF EXISTS";
// $pdo = new BasicQuery($db);
// $pdo->execute($comment_table_query, []);

$comment_table_query = "CREATE TABLE IF NOT EXISTS comment (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `id_picture` INT NOT NULL,
    `id_user` INT NOT NULL,
    `comment` VARCHAR (900) NOT NULL,
    `likes` INT,
    `liked` VARCHAR (1200) NOT NULL,
    `created` DATETIME NOT NULL)";
$pdo = new BasicQuery($db);
$pdo->execute($comment_table_query, []);


//go through each post and add a random numbers of comment from random users from a random date

//get nb of users
$pdo = new BasicQuery($db);
$nb_of_user= intval($pdo->execute("SELECT COUNT(*) FROM users", [])->fetch(\PDO::FETCH_ASSOC)['COUNT(*)']);

//get comment dictionnary
$dic = json_decode(file_get_contents('../config/citation.json'));
//dump(json_decode(file_get_contents('../config/citation.json')));

//random date between two dates

//go through each post
$picture = new Picture();
$pic_array = $picture->GetAll($db);
var_dump($pic_array);
foreach($pic_array as $post){
    //nb of com for this post
    $nb_com = mt_rand(0, 5);
    //id of users for this post
    $user_ids = [];
    for ($i = 0; $i < $nb_com; $i++){
        array_push($user_ids, mt_rand(1, $nb_of_user));
    }


    //populate the db with comments
    if (!empty($user_ids)){
        foreach ($user_ids as $user_id){
            //get random quote from dic
            $randomizit = mt_rand(0, count($dic) - 1);
            $quote = $dic[$randomizit][0];
            $author = $dic[$randomizit][1];
            $content = "$quote $author";

            //get random date
            $now = new DateTime();
            $threeYearsAgo = new DateTime("now -3 year");
            $random_date = Date_Operation::randomDateInRange($threeYearsAgo, $now);
            $timestamp_for_comment = $random_date->getTimestamp();
            $date_for_comment = date('Y-m-d G:i:s', $timestamp_for_comment);

            //Add comment
            $comment = new Comment();
            // $tmp_debug = [
            //     $content,
            //     intval($post->id),
            //     $user_id,
            //     $timestamp_for_comment
            // ];
            // dump($tmp_debug);
            $comment->Add($db, substr($content, 0, 899), intval($post->id), $user_id, $date_for_comment);
        }
    }
}
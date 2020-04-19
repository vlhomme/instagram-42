<?php

use Clas\Mail\Sendgrid;
use Clas\Database\BasicQuery;
use Clas\Table\Comment;
use Clas\Table\User;
use Clas\Database\JsCommentExport;
if ( (isset($_GET['publish_comment']) && !empty($_GET['publish_comment'])) || (isset($_GET['publish_comment']) && $_GET['publish_comment'] === '0')) {
    $text = $_GET['publish_comment'];
    // echo $text;
    echo 'commentaire :';
    // dump($text);
    $id_post = $_GET['comment_post_id'];
    // echo $id_post;
    $pdo = new BasicQuery($db);
    $useless_variable = htmlspecialchars($_SESSION["pseudo"]);
    $id_user = intval($pdo->execute('SELECT id FROM `users` WHERE `pseudo` = ?', [$useless_variable])->fetch(\PDO::FETCH_ASSOC)['id']);
    $id_user_who_comments = $id_user;
    // echo 'mec qui poste :';
    // echo $id_user_who_comments;
    // dump($useless_variable);
    


    $pdo = new BasicQuery($db);
    $id_author = $pdo->execute('SELECT id_user FROM `pictures` WHERE `id` = ?', [$id_post])->fetch(\PDO::FETCH_ASSOC)['id_user'];

    $user1 = new User();
    $user1->import($db, $id_author);
    echo 'auteur ' . $id_author;
    // dump($user1);
    // dump($user1);
    $dest = $user1->mail;
    $prenom = $user1->prenom;
    $nom = $user1->nom;
    if (intval($user1->admin) === 1) {
        $doesNotWantMail = true;
    }

    // dump($doesNotWantMail);

    $date = date('Y-m-d G:i:s');
    $pdo = new BasicQuery($db);
    $date_photo =  $pdo->execute('SELECT `created` FROM `pictures` WHERE `id` = ?', [$id_post])->fetch(\PDO::FETCH_ASSOC)['created'];

    $message = "A $date, $useless_variable a laisse un commentaire sur l'une de vos photos publié le $date_photo! <br> $text <br> Consultez vite votre profil sur Pic One <a href=\"http://localhost:8001\">ici</a>";

    $comment = new Comment();
    $comment->Add($db, $text, $id_post, $id_user);
    // dump($dest);
    // dump($prenom);
    // dump($nom);
    // dump($useless_variable);
    // dump($message);

    if (intval($id_author) !== intval($id_user_who_comments) && !$doesNotWantMail){
        $mail = new Sendgrid("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");
        $mail->sendmail(
            "$dest",
            "camagru-official@vlhommetavelino.42.fr",
            "$prenom $nom",
            "$useless_variable a laisse un commentaire sur votre photo",
            "$message"
        );
        echo 'mail send';
    }
    echo $date;


//like_post (update over value)
} elseif ( isset($_GET['id__post']) && !empty($_GET['id__post']) ) {
    $id = $_GET['id__post'];
    $is_liked = $_GET['comment_post_id'];
    $pdo = new BasicQuery($db);
    $useless_variable = htmlspecialchars($_SESSION["pseudo"]);
    $id_connected_user = intval($pdo->execute('SELECT id FROM `users` WHERE `pseudo` = ?', [$useless_variable])->fetch(\PDO::FETCH_ASSOC)['id']);
    
    //incremente ou decrementer les likes du post + update les liked du post avec l'utilisateur connecte
    if ($is_liked === 'true') {
        $pdo = new BasicQuery($db);
        $query = 'update pictures set likes = (likes - 1) where id = ?';
        $params = [$id];
        $pdo->execute($query, $params);

        //get strings where the ids of people who liked are stored
        $pdo = new BasicQuery($db);
        $arr_of_liked = json_decode($pdo->execute('SELECT liked from `pictures` WHERE id = ?', [$id])->fetch(\PDO::FETCH_ASSOC)['liked']);
        $arr_of_liked = (array) $arr_of_liked;
        //check if user connected is in there and delete him if it's the case
        if (($key = array_search($id_connected_user, $arr_of_liked)) !== false) {
            unset($arr_of_liked[$key]);
        }
        //put back the new string in the database
        $new_array_of_liked = json_encode($arr_of_liked);
        $pdo = new BasicQuery($db);
        $pdo->execute("UPDATE `pictures` SET `liked` = ? WHERE `id` = ?", [$new_array_of_liked, $id]);

        // $tmp = $db->prepare($query);
        // $ret = $tmp->execute($params);
        // echo $ret;
        // $tmp->errorInfo();
        // echo 'suppressed';
    } else {
        $pdo = new BasicQuery($db);
        $query = 'update pictures set likes = (likes + 1) where id = ?';
        $params = [$id];
        $pdo->execute($query, $params);

        //get strings where the ids of people who liked are stored
        $pdo = new BasicQuery($db);
        $arr_of_liked = json_decode($pdo->execute('SELECT liked from `pictures` WHERE id = ?', [$id])->fetch(\PDO::FETCH_ASSOC)['liked']);
        $arr_of_liked = (array) $arr_of_liked;
        //check if user connected is in there and add him if it's not the case
        if (($key = array_search($id_connected_user, $arr_of_liked)) === false) {
            array_push($arr_of_liked, $id_connected_user);
        }
        //put back the new string in the database
        $new_array_of_liked = json_encode($arr_of_liked);
        $pdo = new BasicQuery($db);
        $pdo->execute("UPDATE `pictures` SET `liked` = ? WHERE `id` = ?", [$new_array_of_liked, $id]);

        // $tmp = $db->prepare($query);
        // $ret = $tmp->execute($params);
        // echo $ret;
        // $tmp->errorInfo();
        // echo 'added';
    }
    //nouveau nombre de like + personnes likee
    $pdo = new BasicQuery($db);
    $response = json_encode($pdo->execute("select likes from pictures where id = ?", [$id])->fetch(\PDO::FETCH_ASSOC));
    echo $response;


//like commentaire
} elseif (isset($_GET['comment_id']) && !empty($_GET['comment_id'])) { 
    $is_liked = $_GET['comment_post_id'];
    $id = $_GET['comment_id'];
    $pdo = new BasicQuery($db);
    $useless_variable = htmlspecialchars($_SESSION["pseudo"]);
    $id_connected_user = intval($pdo->execute('SELECT id FROM `users` WHERE `pseudo` = ?', [$useless_variable])->fetch(\PDO::FETCH_ASSOC)['id']);

    if ($is_liked === 'true') {
        $pdo = new BasicQuery($db);
        $query = 'update comment set likes = (likes - 1) where id = ?';
        $params = [$id];
        $pdo->execute($query, $params);

        //get strings where the ids of people who liked are stored
        $pdo = new BasicQuery($db);
        $arr_of_liked = json_decode($pdo->execute('SELECT liked from `comment` WHERE id = ?', [$id])->fetch(\PDO::FETCH_ASSOC)['liked']);
        $arr_of_liked = (array) $arr_of_liked;
        //check if user connected is in there and delete him if it's the case
        if (($key = array_search($id_connected_user, $arr_of_liked)) !== false) {
            unset($arr_of_liked[$key]);
        }
        //put back the new string in the database
        $new_array_of_liked = json_encode($arr_of_liked);
        $pdo = new BasicQuery($db);
        $pdo->execute("UPDATE `comment` SET `liked` = ? WHERE `id` = ?", [$new_array_of_liked, $id]);
        // $tmp = $db->prepare($query);
        // $ret = $tmp->execute($params);
        // echo $ret;
        // $tmp->errorInfo();
        echo 'suppressed';
    } else {
        $pdo = new BasicQuery($db);
        $query = 'update comment set likes = (likes + 1) where id = ?';
        $params = [$id];
        $pdo->execute($query, $params);

                //get strings where the ids of people who liked are stored
                $pdo = new BasicQuery($db);
                $arr_of_liked = json_decode($pdo->execute('SELECT liked from `comment` WHERE id = ?', [$id])->fetch(\PDO::FETCH_ASSOC)['liked']);
                $arr_of_liked = (array) $arr_of_liked;
                //check if user connected is in there and add him if it's not the case
                if (($key = array_search($id_connected_user, $arr_of_liked)) === false) {
                    array_push($arr_of_liked, $id_connected_user);
                }
                //put back the new string in the database
                $new_array_of_liked = json_encode($arr_of_liked);
                $pdo = new BasicQuery($db);
                $pdo->execute("UPDATE `comment` SET `liked` = ? WHERE `id` = ?", [$new_array_of_liked, $id]);
        // $tmp = $db->prepare($query);
        // $ret = $tmp->execute($params);
        // echo $ret;
        // $tmp->errorInfo();
        echo 'added';
    }

} else {
    $id = $_GET['comment_post_id'];
    $comment = new Comment;
    $comments = $comment->GetAll($db, "WHERE id_picture = $id ORDER BY `created` ASC");
    // dump($comments);
    $pdo = new BasicQuery($db);
    $useless_variable = htmlspecialchars($_SESSION["pseudo"]);
    // dump($useless_variable);
    $id_connected_user = intval($pdo->execute('SELECT id FROM `users` WHERE `pseudo` = ?', [$useless_variable])->fetch(\PDO::FETCH_ASSOC)['id']);
    // dump($id_connected_user);
    $comments_arr = [];

    for ($i = 0; $i < count($comments); $i += 3) {
        // dump($comments[$i]);
        // dump($comments[$i + 1][0]->path);
        $array_of_liked = (array)json_decode($comments[$i]->liked);
        if (array_search($id_connected_user, $array_of_liked) !== false) {
            $is_liked = true;
        } else {
            $is_liked = false;
        }
        $JsCommentExport = new JsCommentExport($comments[$i + 1][0]->path, $comments[$i + 2]['pseudo'], $comments[$i]->comment, $comments[$i]->created, $comments[$i]->likes, $comments[$i]->id, $comments[$i]->liked, $is_liked);
        $comments_arr[] = $JsCommentExport;
    }

    $response = json_encode($comments_arr);
    echo $response;
}
<?php
use Clas\Database\BasicQuery;
use Clas\Table\Picture;

if (isset($_SESSION['montage'])) {
    
    if (isset($_GET['disconnect_me']) && !empty($_GET['disconnect_me'])) {
        require_once '../control/connected/disconnect_me.php';
        // header('Location: ./');
    } elseif (isset($_GET['go_back']) && !empty($_GET['go_back'])){
        require '../control/go_back.php';
    } elseif (isset($_GET['publish_picture']) && !empty($_GET['publish_picture'])){
        require '../control/publication.php';
    } elseif (isset($_GET['stick']) && !empty($_GET['stick'])) {
        $stickers = glob('img/ico/sticker_*.png');
        require '../template/edit2.html';
    } elseif (isset($_FILES['fichier']) && !empty($_FILES['fichier'])) {
        require '../control/upload_picture.php';
    } elseif (isset($_POST['filter']) && !empty($_POST['filter'])) {
        require_once '../control/create_picture_filter.php';
    } else {
        // dump( $_FILES );
        $stickers = glob('img/ico/sticker_*.png');
        $pdo = new BasicQuery($db);
        $pseudu = htmlspecialchars($_SESSION['pseudo']);
        $idi = $pdo->execute('SELECT `id` from `users` where `pseudo` = ?', [$pseudu])->fetch(\PDO::FETCH_ASSOC)['id'];
        $picture =  new Picture();
        $posts = $picture->GetAll($db, "WHERE id_user = $idi ORDER BY `created` DESC");
        // dump($posts);
        require '../template/edit2.html';
    }
} else {
    if (isset($_GET['notification_toggle'])) {
        require_once '../control/connected/notification_toggle.php';
    } elseif (isset($_POST['new_pass1'])) {
        require_once '../control/connected/new_pass.php';
    } elseif (isset($_GET['update_standard_info']) && !empty($_GET['update_standard_info']) ) {
        require_once '../control/connected/update_standard_info.php';
    } elseif (isset($_GET['get_mail']) && !empty($_GET['get_mail'])) {
        require_once '../control/connected/get_mail.php';
    } elseif (isset($_GET['disconnect_me']) && !empty($_GET['disconnect_me'])) {
        require_once '../control/connected/disconnect_me.php';
    } elseif (isset($_GET['gallery']) && !empty($_GET['gallery'])) {
        require_once '../control/gallery.php';
        require '../template/gallery.html';
        // require '../control/connection/connection.php';
    } elseif (isset($_GET['next_gallery']) && !empty($_GET['next_gallery'])) {
        require_once '../control/next_gallery.php';
    } elseif (isset($_GET['suppress_comment']) && !empty($_GET['suppress_comment'])) {
        require_once '../control/connected/suppression_commentaire.php';
    } elseif (isset($_GET['suppress_post']) && !empty($_GET['suppress_post'])) {
        require_once '../control/connected/suppression_image.php';
    } elseif (isset($_GET['IWANTMYINFO']) && !empty($_GET['IWANTMYINFO'])) {
        require_once '../control/connected/myinfo.php';
    } elseif (isset($_GET['set_montage']) && !empty($_GET['set_montage'])) {
        require_once '../control/connected/go_to_montage.php';
    } elseif (isset($_GET['post_id']) && !empty($_GET['post_id'])) {
        require_once '../control/connected/post.php';
    } elseif (isset($_GET['comment_post_id']) && !empty($_GET['comment_post_id'])) {
        require_once '../control/connected/comment.php';
    } elseif (isset($_GET['another_pseudo'])) {
        $pseudu = $_GET['another_pseudo'];
        echo "<div aria-hidden='true' style='display: none;' class='is_not_me_uglyfix'>NO$pseudu</div>";
        require_once '../control/another_pseudo.php';
        require_once '../control/connected/user.php';
    } elseif (isset($_POST['filter']) && !empty($_POST['filter'])) {
        require_once '../control/create_picture_filter.php';
    } elseif (isset($_GET['public_info']) && !empty($_GET['public_info'])) {
        require_once '../control/my_pseudo.php';
        require '../control/fix_some_vulnerabilities.php';
    }
    else {
        require_once '../control/my_pseudo.php';
        require_once '../control/connected/user.php';
    }
}

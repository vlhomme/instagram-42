<?php
session_start();
date_default_timezone_set('Europe/Paris');

use Clas\Database\BasicQuery;
use Clas\Image_filter;
use Clas\Table\Picture;
use Clas\Table\ProfilePicture;
use Clas\GeneratePass;
use Clas\Upload;

$value = file_get_contents('./init');

require 'autoloader.php';
Autoloader::register();

if (intval($value) === 0) {
    require_once '../config/setup.php';
    file_put_contents('./init', '1');
    die();
}
require_once '../config/database.php';


// $pdo = new BasicQuery($db);
// $ret = $pdo->execute('use CAMAGRU', []);
// // dump($ret);
// die();
//  require_once '../config/setup.php';
//  die();

// $lesphotosdenaceur = glob('./img/publication*');
// if ($lesphotosdenaceur === false || $lesphotosdenaceur === []) {
//     echo 'error for naceur\'s photos';
//     die();
// } else {
//     foreach($lesphotosdenaceur as $photoDenaceur) {
//         // $picture = new Picture();
//         // $picture->Add($db, $photoDenaceur, )
//         // dump($photoDenaceur);
//         dump(strstr($photoDenaceur, '/img'));
//     }
// }

// phpinfo();
// die();

//send data :
// 1. path to original picture.
// 2. path to picture with filters


// // $original_picture = 'img/profile_pic_vi.jpg';
// $original_picture = 'img/profile_pic_jo.jpg';

// //delete all tmp file and recreate them from original picture
// $filter_names = Image_filter::get_filter_names();
// foreach ($filter_names as $filters) {
//     Image_filter::create_filter($filters, $original_picture);
// }

// require '../template/edit.html';
// die();

//tmp :
// $_SESSION["pseudo"] = 'Adrien Romain2';

// $_SESSION["pseudo"] = 'Louis XVI';
// $_SESSION["pseudo"] = 'Stephane Bern';
// $_SESSION["pseudo"] = 'VL';
// $_SESSION['montage'] = true;

if (isset($_SESSION['pseudo'])) {
    // dump ($_SESSION['pseudo']);
    require_once '../control/connected.php';
} else {
    require_once '../control/connection.php';
}

?>
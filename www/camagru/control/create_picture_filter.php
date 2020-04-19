<?php
use Clas\Image_filter;
$data = $_POST['filter'];
$sticker = $_POST['sticker'];
if ($sticker === '' || $sticker === "/" || $sticker === 'http://localhost:8001/' || $sticker === 'https://localhost/'){
    $sticker_empty = true;
}
// echo '**';
// echo $sticker;
// echo '**';

$data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $data));

file_put_contents('img/attempt.png', $data);

// $original_picture = 'img/profile_pic_vi.jpg';
if ($sticker_empty){
    // echo 'yes';
    $original_picture = 'img/attempt.png';
    imagepng(imagecreatefrompng('img/attempt.png'), 'img/attempt.png');
} else {
    $original_picture = Image_filter::superpose('img/attempt.png', $sticker);
}

$prouto = [];

//delete all tmp file and recreate them from original picture
$filter_names = Image_filter::get_filter_names();
foreach ($filter_names as $filters) {
    Image_filter::create_filter($filters, $original_picture);
    array_push($prouto, 'img/' . $filters . '.jpg');
}

$export = [
    'limage' => $original_picture,
    'lesfiltres' => $prouto
];

echo (json_encode($export));
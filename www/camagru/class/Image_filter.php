<?php

namespace Clas;

class Image_filter {

    /**
     * toutes les experimentations meridques que j'ai fait avant |
     *                                                           |
     *                                                          \./
     * 
     * // Création d'une image vide et ajout d'un texte
     * $im = imagecreatetruecolor(120, 20);
     * $text_color = imagecolorallocate($im, 233, 14, 91);
     * imagestring($im, 1, 5, 5,  "Un texte simple", $text_color);

     * Affichage de l'image
     * imagegd($im);
     * 
     * Libération de la mémoire
     * imagedestroy($im);
     * 
     * header ('Content-type: image/png');
     * 
     * $im = @imagecreatetruecolor(120, 20) or die('Cannot Initialize new GD image stream');
     * $text_color = imagecolorallocate($im, 233, 14, 91);
     * imagestring($im, 1, 5, 5, 'A Simple Text String', $text_color);
     * imagepng($im);
     * imagedestroy($im);exit;
     * 
     * echo ini_get('upload_max_filesize');
     * $im = imagecreatefromjpeg('img/profile_pic_vi.jpg');
     * $im = imagecreatefromjpeg('img/2.jpg');
     * $im2 = imagecreatefromjpeg('img/profile_pic_vi.jpg');
     * $im2 = imagecreatefrompng('img/ico/down.png');
     * imagefilter($im,IMG_FILTER_GRAYSCALE);
     * imagefilter($im, IMG_FILTER_CONTRAST, -20);
     * imagefilter($im,IMG_FILTER_BRIGHTNESS, 60);
     * imagefilter($im,IMG_FILTER_SMOOTH, 60);
     * imagefilter($im,IMG_FILTER_COLORIZE, 90, 55, 30);
     * imagefilter($im,IMG_FILTER_COLORIZE, 90, 55, 30);
     * 
     * $test = exif_read_data('img/IMG_1282.jpg');
     * dump($test);
     * imagejpeg($im, 'img/test.jpg');
     * $im = imagecreatefromjpeg('img/profile_pic_jo.jpg');
     * Image_filter::sepia($im);
     * header("Content-type: image/jpeg");
     * imagejpeg($im);
     * die();
     */

    function superpose($im, $sticker) {
        $path_sticker = substr($sticker, 22);
        $im = imagecreatefrompng($im);
        $sticker = imagecreatefrompng($path_sticker);

        $width = 200;
        $height = 200;
        list($width_orig, $height_orig) = getimagesize($path_sticker);
        $ratio_orig = $width_orig/$height_orig;
        if ($width/$height > $ratio_orig) {
            $width = $height*$ratio_orig;
         } else {
            $height = $width/$ratio_orig;
         }
         $image_p = imagecreatetruecolor($width, $height);
         $black = imagecolorallocate($image_p, 0, 0, 0);
         imagecolortransparent($image_p, $black);
        //  header("Content-type: image/png");
        //  imagepng($image_p);
        //  die();
         imagecopyresampled($image_p, $sticker, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);
        //  header("Content-type: image/png");
        // imagepng($image_p);
        // die();
        imagecopy($im, $image_p, 0, 0, 0, 0, $width, $height);
        $file = 'img/attempt.png';
        file_exists($file) ? unlink($file) : '';
        imagepng($im, $file);
        return($file);
    }

    function sepia ($im) {
        imagefilter($im,IMG_FILTER_GRAYSCALE);
        imagefilter($im, IMG_FILTER_CONTRAST, -10);
        imagefilter($im,IMG_FILTER_COLORIZE, 60, 35, 15);
        return($im);
    }
    
    function golden_boost ($im) {
        // imagefilter($im,IMG_FILTER_GRAYSCALE);
        self::sepia($im);
        $x = imagesx($im);
        $y = imagesy($im);
        $prout = imagecreatetruecolor($x, $x);
        imagefilledrectangle($prout, 0, 0, $x, $x, imagecolorallocate($prout, 20, 20, 20));
        imagelayereffect($im, IMG_EFFECT_OVERLAY);
        imagecopy($im, $prout, 0, 0, 0, 0, $x, $y);
        // imagefilter($im, IMG_FILTER_CONTRAST, -10);
        // imagefilter($im,IMG_FILTER_COLORIZE, 100, 35, 15);
        return ($im);
    }
    
    function a_touch_of_love($im){
        imagefilter($im,IMG_FILTER_COLORIZE, 60, 0, 0);
        return($im);
    }

    function get_filter_names () {
        return $filter_names = [
            'golden_boost',
            'sepia',
            'a_touch_of_love'
        ];
    }

    function create_filter($filter_name, $original_picture) {
        $file = 'img/' . $filter_name . '.jpg';
        file_exists($file) ? unlink($file) : '';
        imagejpeg(Image_filter::$filter_name(imagecreatefrompng($original_picture)), $file);
    }
}
<?php
namespace Clas;

class Upload {
    function verify_and_upload ($lefichier, $prefix = '') {
        
    /*HOW TO USE ?
        *
        *
    echo '<form enctype="multipart/form-data" action="/" method="post"><input type="file" name="fichier"><input type="submit" name="valider"></form>';

    if (isset($_FILES['fichier'])) {
        echo (Upload::verify_and_upload($_FILES['fichier']));
    };
        */


            // dump($_FILES);
        
            $error = 'OK';
            $fichier = $lefichier;
            // dump($fichier);
            $actual_name = $fichier['tmp_name'];
            $ext = pathinfo($fichier['name'], PATHINFO_EXTENSION);
        
            $hash = GeneratePass::sha('');
            $hash = str_replace('$argon2i$v=19$m=2048,t=4,p=3$', '', $hash);
            $hash = substr($hash, 0, 10);
            if ($prefix !== ''){
                $path = "./img/$prefix";
            } else {
                $path = './img/publication';                
            }
            $ok_ext = [
                "JPG",
                "PNG",
                "jpg",
                "png"
            ];
            $legalSize = "1800000"; // 1800000 Octets = 1.8 MO

            if ($legalSize < $fichier['size']){
                $error = 'fichier trop gros';
            }

            if ($actual_name == 0 || $fichier['size'] == 0) {
                $error = 'fichier vide';
            }
        
        
            $usable_name = $path . $hash . '.' . $ext;
            // dump($usable_name);
        
            if (file_exists($usable_name)) {
                $error = 'internal error';
            }
        
            if ($error !== 'OK'){
                if (in_array($ext, $ok_ext)){
                    $ret = move_uploaded_file($actual_name, $usable_name);
                    // dump($ret);
                } else {
                    $ret = false;
                    $error = 'seulement les formats jpg et png sont autorisés';
                }
            }
            
            $usable_name = substr($usable_name, 1);
            // echo "<img src=\"$usable_name\">";
            // dump($hash);
            if ($ret === true) {
                return $usable_name;
            } else {
                return $error;
            }
        }
}
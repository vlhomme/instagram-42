<?php
$_SESSION['montage'] = true;
if (isset($_SESSION['montage'])) {
    echo 'OK';
} else {
    echo 'ma bite';
}
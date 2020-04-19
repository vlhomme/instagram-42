<?php
use Clas\GeneratePass;
use Clas\Database\BasicQuery;
$DB_DSN = "mysql:host=db";
$DB_USER = 'root';
$DB_PASSWORD = '123456';
$db = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
if ($db === null)
{
    echo "Problem occured while connecting";
    die();
}
$res = $db->query("DROP DATABASE IF EXISTS `CAMAGRU`");
if ($res === false)
{
    echo "Problem occured while droping db";
    die();
}
$res = $db->query("CREATE DATABASE CAMAGRU");
if ($res === false)
{
    echo "Problem occured while creating";
    die();
}
$res = $db->query("use CAMAGRU");
if ($res === false)
{
    echo "Problem occured while using db";
    die();
}
// die();
require_once 'setupUser.php';
// die();
require_once 'setupPicture.php';
// die();
// require_once 'setupProfilePicture.php';

require_once 'setupComment.php';
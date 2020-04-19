<?php
use Clas\Database\BasicQuery;
use Clas\Table\User;
$DB_DSN = "mysql:host=db;dbname=CAMAGRU";
$DB_USER = 'root';
$DB_PASSWORD = '123456';
$db = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
if ($db === null)
{
    echo "Problem occured while connecting";
    die();
}
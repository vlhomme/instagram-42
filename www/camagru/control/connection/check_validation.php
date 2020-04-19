<?php
use Clas\Table\User;

$user = new User();
$user->Import($db, $_GET['id']);
$str = $user->ConfirmUser($_GET['valid']);
echo "<div class=\"js-check-validation\" style=\"display: none;\">$str</div>";
require '../template/test.html';
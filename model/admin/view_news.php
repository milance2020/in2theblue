<?php
if (empty($_SESSION['ulogovan']) || $_SESSION['ulogovan'] !== 1) {
    die('Zabranjen pristup');
}
if (!defined('DIR_ROOT')) {
    require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'constants.php';
}
include FILE_CONNECT;
$upit = 'SELECT * FROM news';
$q = mysqli_query($conn,$upit);
$news = [];
while ($row = mysqli_fetch_object($q)) {
    $news[] = $row;
}

?>
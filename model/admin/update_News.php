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
$id = $_GET['id'] ?? 0; // id proizvoda koji želimo urediti, npr. index.php?page=adminPanel&view=update&id=2
$result = $conn->query("SELECT * FROM news where id='$id'");
$product = null;
if ($result->num_rows > 0) {
    $product = $result->fetch_object(); // jedan red kao object
} else {
    $product = null;
}


?>

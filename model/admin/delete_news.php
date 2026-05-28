<?php


$id = $_GET['id'] ?? 0; // id proizvoda koji želimo obrisati, npr. index.php?page=adminPanel&action=delete&id=2 
if (!defined('DIR_ROOT')) {
    require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'constants.php';
}
include FILE_CONNECT;

//dobijanje putanje slike da bi se obrisala sa servera
$stmt = $conn->prepare("SELECT image FROM news WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $image_path = $row['image'];
    if (file_exists($image_path)) {
        unlink($image_path); // brišemo sliku sa servera
    }else {
        echo "⚠️ Slika nije pronađena na serveru.";
    }
} else {
    echo "❌ Vijest nije pronađena u bazi.";}


//brisanje vijesti iz baze

$stmt = $conn->prepare("DELETE FROM news WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header("Location: index.php?page=adminPanel&view=viewNews");
} else {
    echo "❌ Greška: " . $stmt->error;
}
?>
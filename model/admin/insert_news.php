<?php
if (!defined('DIR_ROOT')) {
    require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'constants.php';
}
include FILE_CONNECT;
$title = $_POST['title'] ?? '';
$content = $_POST['content'] ?? '';
$putanja_slike = null;
$kategorija = $_POST['category'] ?? '';

if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $folder = DIR_ASSETS_IMAGES_NEWS;
    if (!is_dir($folder)) {
        mkdir($folder, 0777, true);
    }
    $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
    $novo_ime = uniqid("product_", true) . "." . strtolower($ext);
    $putanja_slike = $folder . $novo_ime;
    $putanja_slike_baza = URL_ASSETS_IMAGES_NEWS . $novo_ime;

    if (move_uploaded_file($_FILES['image']['tmp_name'], $putanja_slike)) {
    // uspješno
} else {
    echo "❌ Upload slike nije uspio!";
    exit;
}
}
$stmt = $conn->prepare("INSERT INTO news (title, content, image, category) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $title, $content, $putanja_slike_baza, $kategorija);
if ($stmt->execute()) {
    header("Location: /v5/index.php?page=adminPanel");
    
} else {
    echo "❌ Greška: " . $stmt->error;
}
$stmt->close();
$conn->close();
?>


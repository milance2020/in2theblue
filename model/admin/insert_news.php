<?php

if (!defined('DIR_ROOT')) {
    require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'constants.php';
}

require_once FILE_SECURITY_HELPER;
require_moderator();
csrf_verify_or_die();

include FILE_CONNECT;

$title = trim($_POST['title'] ?? '');
$content = trim($_POST['content'] ?? '');
$category = trim($_POST['category'] ?? '');
$imagePathForDb = null;

if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $folder = DIR_ASSETS_IMAGES_NEWS;

    if (!is_dir($folder)) {
        mkdir($folder, 0777, true);
    }

    $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
    $newName = uniqid("news_", true) . "." . $ext;
    $imagePathOnDisk = $folder . $newName;
    $imagePathForDb = URL_ASSETS_IMAGES_NEWS . $newName;

    if (!move_uploaded_file($_FILES['image']['tmp_name'], $imagePathOnDisk)) {
        exit("Upload slike nije uspio.");
    }
}

$stmt = $conn->prepare("
    INSERT INTO news (title, content, image, category)
    VALUES (?, ?, ?, ?)
");

$stmt->bind_param("ssss", $title, $content, $imagePathForDb, $category);

if ($stmt->execute()) {
    flash_set('success', 'Vijest je uspješno dodana.');
    header("Location: /v5/index.php?page=adminPanel&view=viewNews");
    exit;
}

flash_set('error', 'Greška pri dodavanju vijesti.');
header("Location: /v5/index.php?page=adminPanel&view=insertNews");
exit;

<?php

if (!defined('DIR_ROOT')) {
    require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'constants.php';
}

require_once FILE_SECURITY_HELPER;
require_admin();
csrf_verify_or_die();

include FILE_CONNECT;

$id = (int) ($_GET['id'] ?? 0);
$title = trim($_POST['title'] ?? '');
$content = trim($_POST['content'] ?? '');
$category = trim($_POST['category'] ?? '');

if ($id <= 0) {
    exit('Invalid news ID');
}

$stmt = $conn->prepare("
    UPDATE news
    SET title = ?,
        content = ?,
        category = ?
    WHERE id = ?
");

$stmt->bind_param("sssi", $title, $content, $category, $id);

if ($stmt->execute()) {
    flash_set('success', 'Vijest je uspješno ažurirana.');
    header("Location: " . newsUrl(['id' => $id]));
    exit;
}

flash_set('error', 'Greška pri ažuriranju vijesti.');
header("Location: index.php?page=adminPanel&view=updateNews&id=" . $id);
exit;

<?php

if (!defined('DIR_ROOT')) {
    require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'constants.php';
}

require_once FILE_SECURITY_HELPER;
require_admin();
csrf_verify_or_die();

include FILE_CONNECT;

$id = (int) ($_GET['id'] ?? 0);

if ($id <= 0) {
    exit('Invalid news ID');
}

$stmt = $conn->prepare("SELECT image FROM news WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $imagePath = $row['image'];

    if ($imagePath && file_exists($imagePath)) {
        unlink($imagePath);
    }
}

$stmt = $conn->prepare("DELETE FROM news WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    flash_set('success', 'Vijest je obrisana.');
    header("Location: index.php?page=adminPanel&view=viewNews");
    exit;
}

flash_set('error', 'Greska pri brisanju vijesti.');
header("Location: index.php?page=adminPanel&view=viewNews");
exit;

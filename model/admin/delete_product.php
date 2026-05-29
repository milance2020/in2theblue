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
    flash_set('error', 'Neispravan ID proizvoda.');
    header("Location: index.php?page=adminPanel&view=view");
    exit;
}

$stmt = $conn->prepare("
    SELECT image_path
    FROM products2
    WHERE id = ?
    AND deleted_at IS NULL
");

$stmt->bind_param("i", $id);
$stmt->execute();

$row = $stmt->get_result()->fetch_assoc();

if ($row && !empty($row['image_path'])) {
    $imagePath = $row['image_path'];

    if (file_exists($imagePath)) {
        unlink($imagePath);
    }
}

$stmt = $conn->prepare("
    UPDATE products2
    SET deleted_at = NOW()
    WHERE id = ?
    AND deleted_at IS NULL
");

$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    $stmt2 = $conn->prepare("
        UPDATE product_sizes
        SET stock = 0
        WHERE product_id = ?
    ");

    $stmt2->bind_param("i", $id);
    $stmt2->execute();

    flash_set('success', 'Proizvod je obrisan iz prikaza.');
    header("Location: index.php?page=adminPanel&view=view");
    exit;
}

flash_set('error', 'Greska pri brisanju proizvoda.');
header("Location: index.php?page=adminPanel&view=view");
exit;

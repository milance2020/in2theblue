<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!defined('DIR_ROOT')) {
    require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'constants.php';
}
include FILE_CONNECT;

// =========================
// GET ID (SAFE)
// =========================
$id = (int)($_GET['id'] ?? 0);

if ($id <= 0) {
    exit("Invalid product ID");
}

// =========================
// 1. GET IMAGE PATH
// =========================

$stmt = $conn->prepare("
    SELECT image_path
    FROM products2
    WHERE id = ?
    AND deleted_at IS NULL
");

$stmt->bind_param("i", $id);
$stmt->execute();

$result = $stmt->get_result();
$row = $result->fetch_assoc();

// =========================
// 2. DELETE IMAGE (OPTIONAL)
// =========================
// (možeš i preskočiti ako želiš retain assete)

if ($row && !empty($row['image_path'])) {

    $imagePath = $row['image_path'];

    if (file_exists($imagePath)) {
        unlink($imagePath);
    }
}

// =========================
// 3. SOFT DELETE PRODUCT
// =========================

$stmt = $conn->prepare("
    UPDATE products2
    SET deleted_at = NOW()
    WHERE id = ?
    AND deleted_at IS NULL
");

$stmt->bind_param("i", $id);

if ($stmt->execute()) {

    // =========================
    // OPTIONAL: hide variants logically too
    // =========================
    $stmt2 = $conn->prepare("
        UPDATE product_sizes
        SET stock = 0
        WHERE product_id = ?
    ");

    $stmt2->bind_param("i", $id);
    $stmt2->execute();

    header("Location: index.php?page=adminPanel&view=view");
    exit;

} else {
    echo "❌ Error deleting product: " . $stmt->error;
}
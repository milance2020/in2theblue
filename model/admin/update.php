<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!defined('DIR_ROOT')) {
    require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'constants.php';
}
include FILE_CONNECT;

// =========================
// INPUT DATA
// =========================

$id = (int)($_POST['id'] ?? 0);

$name = trim($_POST['name'] ?? '');
$description = trim($_POST['description'] ?? '');
$price = (float)($_POST['price'] ?? 0);

// stock array (size => qty)
$stock = $_POST['stock'] ?? [];

// =========================
// VALIDATION (basic)
// =========================

if ($id <= 0) {
    exit("Invalid product ID");
}

// =========================
// UPDATE PRODUCT (MAIN TABLE)
// =========================

$stmt = $conn->prepare("
    UPDATE products2
    SET name = ?,
        description = ?,
        price = ?
    WHERE id = ?
");

$stmt->bind_param(
    "ssdi",
    $name,
    $description,
    $price,
    $id
);

$stmt->execute();

// =========================
// UPDATE PRODUCT SIZES (VARIANTS)
// =========================

foreach ($stock as $size => $qty) {

    $qty = (int)$qty;

    // check if row exists
    $check = $conn->prepare("
        SELECT id
        FROM product_sizes
        WHERE product_id = ?
        AND size = ?
    ");

    $check->bind_param("is", $id, $size);
    $check->execute();
    $res = $check->get_result();

    if ($res->num_rows > 0) {

        // UPDATE
        $stmt = $conn->prepare("
            UPDATE product_sizes
            SET stock = ?
            WHERE product_id = ?
            AND size = ?
        ");

        $stmt->bind_param("iis", $qty, $id, $size);
        $stmt->execute();

    } else {

        // INSERT
        $stmt = $conn->prepare("
            INSERT INTO product_sizes (product_id, size, stock)
            VALUES (?, ?, ?)
        ");

        $stmt->bind_param("isi", $id, $size, $qty);
        $stmt->execute();
    }
}

// =========================
// RESPONSE
// =========================

echo "✅ Product successfully updated!";
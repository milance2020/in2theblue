<?php

if (!defined('DIR_ROOT')) {
    require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'constants.php';
}

require_once FILE_SECURITY_HELPER;
require_admin();
csrf_verify_or_die();

include FILE_CONNECT;

$id = (int) ($_POST['id'] ?? 0);
$name = trim($_POST['name'] ?? '');
$description = trim($_POST['description'] ?? '');
$price = (float) ($_POST['price'] ?? 0);
$stock = $_POST['stock'] ?? [];

if ($id <= 0) {
    exit("Invalid product ID");
}

$stmt = $conn->prepare("
    UPDATE products2
    SET name = ?,
        description = ?,
        price = ?
    WHERE id = ?
");

$stmt->bind_param("ssdi", $name, $description, $price, $id);
$stmt->execute();

foreach ($stock as $size => $qty) {
    $qty = (int) $qty;

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
        $stmt = $conn->prepare("
            UPDATE product_sizes
            SET stock = ?
            WHERE product_id = ?
            AND size = ?
        ");

        $stmt->bind_param("iis", $qty, $id, $size);
        $stmt->execute();
    } else {
        $stmt = $conn->prepare("
            INSERT INTO product_sizes (product_id, size, stock)
            VALUES (?, ?, ?)
        ");

        $stmt->bind_param("isi", $id, $size, $qty);
        $stmt->execute();
    }
}

flash_set('success', 'Proizvod je uspješno ažuriran.');
header("Location: index.php?page=adminPanel&view=update&id=" . $id);
exit;

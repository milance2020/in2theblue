<?php

if (!defined('DIR_ROOT')) {
    require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'constants.php';
}
include FILE_CONNECT;

// =========================
// GET ID (safe cast)
// =========================
$id = (int)($_GET['id'] ?? 0);

$product = null;
$productSizes = [];

if ($id > 0) {

    // =========================
    // PRODUCT FETCH (SAFE)
    // =========================
    $stmt = $conn->prepare("
        SELECT *
        FROM products2
        WHERE id = ?
        LIMIT 1
    ");

    $stmt->bind_param("i", $id);
    $stmt->execute();

    $result = $stmt->get_result();
    $product = $result->fetch_object();

    // =========================
    // SIZES FETCH (VARIANTS)
    // =========================
    if ($product) {

        $stmtSizes = $conn->prepare("
            SELECT size, stock
            FROM product_sizes
            WHERE product_id = ?
            ORDER BY size ASC
        ");

        $stmtSizes->bind_param("i", $id);
        $stmtSizes->execute();

        $resSizes = $stmtSizes->get_result();

        while ($row = $resSizes->fetch_assoc()) {
            $productSizes[] = $row;
        }
    }
}
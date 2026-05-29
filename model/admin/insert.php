<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!defined('DIR_ROOT')) {
    require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'constants.php';
}
require_once FILE_SECURITY_HELPER;
require_admin();
csrf_verify_or_die();

include FILE_CONNECT;

// =====================
// INPUT
// =====================
$code = $_POST['sku'] ?? '';
$name = $_POST['name'] ?? '';
$category_id = (int)($_POST['category_id'] ?? 0);
$gender = $_POST['gender'] ?? 'unisex';
$description = $_POST['description'] ?? '';
$price = (float)($_POST['price'] ?? 0);

// sizes
$size_s = (int)($_POST['size_s'] ?? 0);
$size_m = (int)($_POST['size_m'] ?? 0);
$size_l = (int)($_POST['size_l'] ?? 0);
$size_xl = (int)($_POST['size_xl'] ?? 0);

// =====================
// IMAGE UPLOAD
// =====================
$putanja_slike_baza = null;

if (isset($_FILES['productPicture']) && $_FILES['productPicture']['error'] === UPLOAD_ERR_OK) {

    $folder = DIR_ASSETS_IMAGES_SHOP;

    if (!is_dir($folder)) {
        mkdir($folder, 0777, true);
    }

    $ext = pathinfo($_FILES['productPicture']['name'], PATHINFO_EXTENSION);
    $novo_ime = uniqid("product_", true) . "." . strtolower($ext);

    $putanja_slike = $folder . $novo_ime;
    $putanja_slike_baza = URL_ASSETS_IMAGES_SHOP . $novo_ime;

    move_uploaded_file($_FILES['productPicture']['tmp_name'], $putanja_slike);
}

// =====================
// SLUG
// =====================
$slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name)));

// =====================
// 1. INSERT PRODUCT
// =====================
$stmt = $conn->prepare("
    INSERT INTO products2 
    (sku, category_id, name, slug, gender, description, price, image_path)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?)
");

$stmt->bind_param(
    "sissssds",
    $code,
    $category_id,
    $name,
    $slug,
    $gender,
    $description,
    $price,
    $putanja_slike_baza
);

$stmt->execute();

$product_id = $stmt->insert_id;

// =====================
// 2. INSERT SIZES
// =====================
$stmt2 = $conn->prepare("
    INSERT INTO product_sizes (product_id, size, stock)
    VALUES (?, ?, ?)
");

$sizes = [
    'S' => $size_s,
    'M' => $size_m,
    'L' => $size_l,
    'XL' => $size_xl
];



foreach ($sizes as $size => $stock) {
    $stmt2->bind_param("isi", $product_id, $size, $stock);
    $stmt2->execute();
}
// =====================
// DONE
// =====================
flash_set('success', 'Proizvod je uspjesno dodan.');
header("Location: /v5/index.php?page=adminPanel&view=view");
exit;

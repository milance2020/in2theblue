<?php

$_output['view'] = 'shop/cart';
$_output['html_model'] = 'cart-checkout';
$_output['breadcrumbs_enabled'] = true;

require_once FILE_CONNECT;
require_once FILE_SEO_HELPER;

$cart = $_SESSION['cart'] ?? [];
$action = $_GET['action'] ?? '';

// =========================================================
// CART COUNT HELPER (PURE FUNCTION)
// =========================================================

function cartCount(): int
{
    $count = 0;

    foreach ($_SESSION['cart'] ?? [] as $sizes) {
        foreach ($sizes as $qty) {
            $count += $qty;
        }
    }

    return $count;
}

// =========================================================
// REMOVE ITEM (API ACTION)
// =========================================================

if ($action === 'remove') {

    header('Content-Type: application/json');

    $data = json_decode(file_get_contents("php://input"), true);

    $id   = (int) ($data['id'] ?? 0);
    $size = $data['size'] ?? '';

    if ($id > 0 && $size !== '' && isset($_SESSION['cart'][$id][$size])) {

        unset($_SESSION['cart'][$id][$size]);

        if (empty($_SESSION['cart'][$id])) {
            unset($_SESSION['cart'][$id]);
        }
    }

    echo json_encode([
        'success' => true,
        'count'   => cartCount()
    ]);

    exit;
}

// =========================================================
// BUILD CART VIEW (READ ONLY)
// =========================================================

$items = [];
$total = 0;

if (!empty($cart)) {

    $stmt = $conn->prepare("
        SELECT id, name, price, image_path
        FROM products2
        WHERE id = ?
    ");

    foreach ($cart as $productId => $sizes) {

        $stmt->bind_param("i", $productId);
        $stmt->execute();

        $product = $stmt->get_result()->fetch_assoc();

        if (!$product) continue;

        foreach ($sizes as $size => $qty) {

            $subtotal = $product['price'] * $qty;
            $total += $subtotal;

            $items[] = [
                'id'       => $product['id'],
                'name'     => $product['name'],
                'price'    => $product['price'],
                'qty'      => $qty,
                'size'     => $size,
                'subtotal' => $subtotal,
                'image'    => $product['image_path']
            ];
        }
    }
}

// =========================================================
// SEO
// =========================================================

setSEO('cart');
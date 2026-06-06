<?php

// =========================================================
// CART COUNT
// =========================================================
function cartCount()
{
    $count = 0;

    foreach ($_SESSION['cart'] ?? [] as $sizes) {
        foreach ($sizes as $qty) {
            $count += (int)$qty;
        }
    }

    return $count;
}


// =========================================================
// CART TOTAL 
// =========================================================
function cartTotal($conn)
{
    $total = 0;

    foreach ($_SESSION['cart'] ?? [] as $id => $sizes) {

        $stmt = $conn->prepare("
            SELECT price
            FROM products2
            WHERE id = ?
            LIMIT 1
        ");

        $stmt->bind_param("i", $id);
        $stmt->execute();

        $product = $stmt->get_result()->fetch_assoc();

        if (!$product) continue;

        $price = (float)$product['price'];

        foreach ($sizes as $qty) {
            $total += ((int)$qty * $price);
        }
    }

    return $total;
}


// =========================================================
// GET STOCK
// =========================================================
function getStock($conn, $productId, $size)
{
    $stmt = $conn->prepare("
        SELECT stock
        FROM product_sizes
        WHERE product_id = ?
        AND size = ?
        LIMIT 1
    ");

    $stmt->bind_param("is", $productId, $size);
    $stmt->execute();

    $row = $stmt->get_result()->fetch_assoc();

    return $row ? (int)$row['stock'] : 0;
}


// =========================================================
// ADD TO CART
// =========================================================
function cartAdd($conn, $productId, $size, $qty)
{
    // Server opet provjerava stock, ne vjerujemo samo JavaScriptu.
    if ($productId <= 0 || $qty <= 0 || !$size) {
        return ['success' => false, 'message' => 'Invalid data'];
    }

    $stock = getStock($conn, $productId, $size);

    if ($stock <= 0) {
        return ['success' => false, 'message' => 'Out of stock'];
    }

    $current = $_SESSION['cart'][$productId][$size] ?? 0;
    $newQty  = $current + $qty;

    if ($newQty > $stock) {
        return ['success' => false, 'message' => 'Not enough stock'];
    }

    $_SESSION['cart'][$productId][$size] = $newQty;

    return [
        'success' => true,
        'message' => '',
        'count' => cartCount(),
        'total' => cartTotal($conn)
    ];
}


// =========================================================
// REMOVE ITEM
// =========================================================
function cartRemove($conn, $productId, $size)
{
    if (isset($_SESSION['cart'][$productId][$size])) {
        unset($_SESSION['cart'][$productId][$size]);

        if (empty($_SESSION['cart'][$productId])) {
            unset($_SESSION['cart'][$productId]);
        }
    }

    return [
        'success' => true,
        'message' => '',
        'count' => cartCount(),
        'total' => cartTotal($conn)
    ];
}


// =========================================================
// INCREASE
// =========================================================
function cartIncrease($conn, $productId, $size)
{
    $stock = getStock($conn, $productId, $size);
    $current = $_SESSION['cart'][$productId][$size] ?? 0;

    if ($current >= $stock) {
        return ['success' => false, 'message' => 'Max stock reached'];
    }

    $_SESSION['cart'][$productId][$size]++;

    return [
        'success' => true,
        'count' => cartCount(),
        'total' => cartTotal($conn)
    ];
}


// =========================================================
// DECREASE
// =========================================================
function cartDecrease($conn, $productId, $size)
{
    if (!isset($_SESSION['cart'][$productId][$size])) {
        return [
            'success' => true,
            'count' => cartCount(),
            'total' => cartTotal($conn)
        ];
    }

    $_SESSION['cart'][$productId][$size]--;

    if ($_SESSION['cart'][$productId][$size] <= 0) {
        unset($_SESSION['cart'][$productId][$size]);
    }

    if (empty($_SESSION['cart'][$productId])) {
        unset($_SESSION['cart'][$productId]);
    }

    return [
        'success' => true,
        'count' => cartCount(),
        'total' => cartTotal($conn)
    ];
}


// =========================================================
// GET CART ITEMS
// =========================================================
function cartGet($conn)
{
    // Session cuva samo ID/velicinu/kolicinu, detalje ucitavamo iz baze.
    $items = [];

    foreach ($_SESSION['cart'] ?? [] as $id => $sizes) {

        $stmt = $conn->prepare("
            SELECT id, name, price, image_path
            FROM products2
            WHERE id = ?
        ");

        $stmt->bind_param("i", $id);
        $stmt->execute();

        $product = $stmt->get_result()->fetch_assoc();

        if (!$product) continue;

        $price = (float)$product['price'];

        foreach ($sizes as $size => $qty) {

            $qty = (int)$qty;

            $items[] = [
                'id' => $product['id'],
                'name' => $product['name'],
                'price' => $price,
                'image_path' => $product['image_path'],
                'size' => $size,
                'qty' => $qty,
                'subtotal' => $qty * $price
            ];
        }
    }

    return $items;
}

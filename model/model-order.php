<?php

$_output['view'] = 'shop/order';
$_output['html_model'] = 'order';
$_output['breadcrumbs_enabled'] = true;

require_once FILE_CONNECT;
require_once FILE_SEO_HELPER;

$cart = $_SESSION['cart'] ?? [];

$items = [];
$total = 0;

// =========================================================
// BUILD ORDER PREVIEW (READ ONLY CART → DB ENRICHMENT)
// =========================================================

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

            $items[] = [
                'id' => $product['id'],
                'name' => $product['name'],
                'price' => $product['price'],
                'qty' => $qty,
                'size' => $size,
                'subtotal' => $subtotal,
                'image' => $product['image_path']
            ];

            $total += $subtotal;
        }
    }
}

// =========================================================
// ORDER SUBMIT (WRITE ACTION)
// =========================================================

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name    = trim($_POST['full_name'] ?? '');
    $email   = trim($_POST['email'] ?? '');
    $phone   = trim($_POST['phone'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $city    = trim($_POST['city'] ?? '');
    $zip     = trim($_POST['zip_code'] ?? '');
    $country = trim($_POST['country'] ?? '');

    // -------------------------
    // VALIDATION
    // -------------------------

    if (empty($cart)) {
        $errors[] = "Korpa je prazna";
    }

    if ($name === '') {
        $errors[] = "Ime je obavezno";
    }

    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Email nije validan";
    }

    // =========================================================
    // SAVE ORDER (ONLY IF VALID)
    // =========================================================

    if (empty($errors)) {

        // CUSTOMER
        $stmt = $conn->prepare("
            INSERT INTO customers
            (full_name, email, phone, address, city, zip, country)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");

        $stmt->bind_param(
            "sssssss",
            $name,
            $email,
            $phone,
            $address,
            $city,
            $zip,
            $country
        );

        $stmt->execute();
        $customerId = $stmt->insert_id;

        // ORDER
        $stmt = $conn->prepare("
            INSERT INTO orders
            (customer_id, total_price)
            VALUES (?, ?)
        ");

        $stmt->bind_param("id", $customerId, $total);
        $stmt->execute();

        $orderId = $stmt->insert_id;

        // ORDER ITEMS
        $stmtItem = $conn->prepare("
            INSERT INTO order_items
            (order_id, product_id, size, quantity, price, subtotal)
            VALUES (?, ?, ?, ?, ?, ?)
        ");

        foreach ($items as $item) {

            $stmtItem->bind_param(
                "iisidd",
                $orderId,
                $item['id'],
                $item['size'],
                $item['qty'],
                $item['price'],
                $item['subtotal']
            );

            $stmtItem->execute();
        }

        // CLEAR CART
        unset($_SESSION['cart']);

        header("Location: " . orderSuccessUrl($orderId));
        exit;
    }
}

// =========================================================
// SEO
// =========================================================

setSEO('checkout');
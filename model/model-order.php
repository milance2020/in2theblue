<?php

$_output['view'] = 'shop/order';
$_output['html_model'] = 'order';
$_output['breadcrumbs_enabled'] = true;

require_once FILE_CONNECT;
require_once FILE_SEO_HELPER;
require_once FILE_CART_HELPER;

$cart = $_SESSION['cart'] ?? [];
$items = cartGet($conn);
$total = 0;
$errors = [];

$formData = [
    'full_name' => '',
    'email' => '',
    'phone' => '',
    'address' => '',
    'city' => '',
    'zip_code' => '',
    'country' => '',
];

foreach ($items as $item) {
    $total += (float) $item['subtotal'];
}

// =========================================================
// ORDER SUBMIT
// =========================================================

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_verify_or_die();

    foreach ($formData as $key => $value) {
        $formData[$key] = trim($_POST[$key] ?? '');
    }

    if (empty($cart) || empty($items)) {
        $errors[] = 'Korpa je prazna.';
    }

    if ($formData['full_name'] === '') {
        $errors[] = 'Ime i prezime su obavezni.';
    }

    if ($formData['email'] === '' || !filter_var($formData['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Email nije validan.';
    }

    if ($formData['phone'] === '') {
        $errors[] = 'Telefon je obavezan.';
    }

    if ($formData['address'] === '') {
        $errors[] = 'Adresa je obavezna.';
    }

    if ($formData['city'] === '') {
        $errors[] = 'Grad je obavezan.';
    }

    if ($formData['country'] === '') {
        $errors[] = 'Država je obavezna.';
    }

    foreach ($items as $item) {
        $stock = getStock($conn, (int) $item['id'], (string) $item['size']);

        if ((int) $item['qty'] > $stock) {
            $errors[] = 'Nema dovoljno zaliha za proizvod: ' . $item['name'] . ' (' . $item['size'] . ').';
        }
    }

    if (empty($errors)) {
        $conn->begin_transaction();

        try {
            $stmt = $conn->prepare("
                INSERT INTO customers
                (full_name, email, phone, address, city, zip, country)
                VALUES (?, ?, ?, ?, ?, ?, ?)
            ");

            if (!$stmt) {
                throw new Exception('Greška pri pripremi kupca.');
            }

            $stmt->bind_param(
                'sssssss',
                $formData['full_name'],
                $formData['email'],
                $formData['phone'],
                $formData['address'],
                $formData['city'],
                $formData['zip_code'],
                $formData['country']
            );

            if (!$stmt->execute()) {
                throw new Exception('Kupac nije spremljen.');
            }

            $customerId = $stmt->insert_id;

            $stmt = $conn->prepare("
                INSERT INTO orders
                (customer_id, total_price)
                VALUES (?, ?)
            ");

            if (!$stmt) {
                throw new Exception('Greška pri pripremi narudžbe.');
            }

            $stmt->bind_param('id', $customerId, $total);

            if (!$stmt->execute()) {
                throw new Exception('Narudžba nije spremljena.');
            }

            $orderId = $stmt->insert_id;

            $stmtItem = $conn->prepare("
                INSERT INTO order_items
                (order_id, product_id, size, quantity, price, subtotal)
                VALUES (?, ?, ?, ?, ?, ?)
            ");

            if (!$stmtItem) {
                throw new Exception('Greška pri pripremi stavki narudžbe.');
            }

            foreach ($items as $item) {
                $productId = (int) $item['id'];
                $size = (string) $item['size'];
                $qty = (int) $item['qty'];
                $price = (float) $item['price'];
                $subtotal = (float) $item['subtotal'];

                $stmtItem->bind_param(
                    'iisidd',
                    $orderId,
                    $productId,
                    $size,
                    $qty,
                    $price,
                    $subtotal
                );

                if (!$stmtItem->execute()) {
                    throw new Exception('Stavka narudžbe nije spremljena.');
                }
            }

            $conn->commit();

            unset($_SESSION['cart']);

            header('Location: ' . orderSuccessUrl($orderId));
            exit;
        } catch (Exception $e) {
            $conn->rollback();
            $errors[] = $e->getMessage();
        }
    }
}

// =========================================================
// SEO
// =========================================================

setSEO('checkout');

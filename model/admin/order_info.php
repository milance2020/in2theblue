<?php

if (!defined('DIR_ROOT')) {
    require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'constants.php';
}

include FILE_CONNECT;

$order_id = (int) ($_GET['id'] ?? 0);
$order = null;
$items = [];

if ($order_id <= 0) {
    http_response_code(404);
    $_output['view'] = 'errors/404';
    return;
}

// =========================================================
// ORDER + CUSTOMER
// =========================================================

$stmt = $conn->prepare("
    SELECT
        orders.*,
        customers.full_name,
        customers.email,
        customers.phone,
        customers.address,
        customers.city,
        customers.zip,
        customers.country
    FROM orders
    JOIN customers
        ON customers.id = orders.customer_id
    WHERE orders.id = ?
    LIMIT 1
");

if ($stmt) {
    $stmt->bind_param('i', $order_id);
    $stmt->execute();
    $order = $stmt->get_result()->fetch_object();
}

if (!$order) {
    http_response_code(404);
    $_output['view'] = 'errors/404';
    return;
}

// =========================================================
// ORDER ITEMS
// =========================================================

$stmt = $conn->prepare("
    SELECT
        order_items.*,
        products2.name,
        products2.image_path
    FROM order_items
    JOIN products2
        ON products2.id = order_items.product_id
    WHERE order_items.order_id = ?
");

if ($stmt) {
    $stmt->bind_param('i', $order_id);
    $stmt->execute();

    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $items[] = $row;
    }
}

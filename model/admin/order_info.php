<?php

if (!defined('DIR_ROOT')) {
    require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'constants.php';
}
include FILE_CONNECT;

$order_id = (int) ($_GET['id'] ?? 0);

/*
|--------------------------------------------------------------------------
| ORDER + CUSTOMER
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare("
    SELECT 
        orders.*,
        customers.full_name,
        customers.email,
        customers.phone,
        customers.address,
        customers.city
    FROM orders
    JOIN customers 
    ON customers.id = orders.customer_id
    WHERE orders.id = ?
");

$stmt->bind_param("i", $order_id);

$stmt->execute();

$order = $stmt->get_result()->fetch_object();

/*
|--------------------------------------------------------------------------
| ORDER ITEMS
|--------------------------------------------------------------------------
*/

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

$stmt->bind_param("i", $order_id);

$stmt->execute();

$result = $stmt->get_result();

$items = [];

while ($row = $result->fetch_assoc()) {

    $items[] = $row;
}
?>
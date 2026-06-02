<?php

$_output['view'] = 'shop/order-success';
$_output['html_model'] = 'order-success';
$_output['breadcrumbs_enabled'] = true;

require_once FILE_CONNECT;
require_once FILE_SEO_HELPER;

$orderId = (int) ($_GET['order_id'] ?? 0);
$order = null;

if ($orderId > 0) {
    $stmt = $conn->prepare("
        SELECT id, total_price, created_at
        FROM orders
        WHERE id = ?
        LIMIT 1
    ");

    if ($stmt) {
        $stmt->bind_param('i', $orderId);
        $stmt->execute();
        $order = $stmt->get_result()->fetch_assoc();
    }
}

if (!$order) {
    http_response_code(404);

    $_output['html_model'] = 'error';
    $_output['view'] = 'errors/404';

    setSEO('error404');
    return;
}

setSEO('order_success');

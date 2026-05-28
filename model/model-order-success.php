<?php
$_output['view'] = 'shop/order-success';
$_output['html_model'] = 'order-success';
$_output['breadcrumbs_enabled'] = true;
require_once FILE_SEO_HELPER;

$orderId = (int) ($_GET['order_id'] ?? 0);

if ($orderId <= 0) {
    die('Invalid order.');
}

setSEO('order_success');
<?php

if (!defined('DIR_ROOT')) {
    require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'constants.php';
}

include FILE_CONNECT;

$status = $_GET['status'] ?? '';

$allowedStatuses = [
    'Pending',
    'Processing',
    'Shipped',
    'Completed',
    'Cancelled'
];

$orders = [];

$baseQuery = "
    SELECT 
        orders.*,
        customers.full_name
    FROM orders
    JOIN customers 
        ON customers.id = orders.customer_id
";

$orderBy = "
    ORDER BY
        CASE
            WHEN orders.status = 'Pending' THEN 1
            WHEN orders.status = 'Processing' THEN 2
            WHEN orders.status = 'Shipped' THEN 3
            WHEN orders.status = 'Completed' THEN 4
            WHEN orders.status = 'Cancelled' THEN 5
            ELSE 6
        END,
        orders.created_at DESC
";

if (in_array($status, $allowedStatuses, true)) {

    $stmt = $conn->prepare($baseQuery . "
        WHERE orders.status = ?
        " . $orderBy
    );

    $stmt->bind_param("s", $status);

} else {

    $stmt = $conn->prepare($baseQuery . $orderBy);
}

$stmt->execute();

$result = $stmt->get_result();

while ($row = $result->fetch_object()) {
    $orders[] = $row;
}
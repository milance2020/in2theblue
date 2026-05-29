<?php

if (!defined('DIR_ROOT')) {
    require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'constants.php';
}

include FILE_CONNECT;

function dashboardCount(mysqli $conn, string $sql): int
{
    $result = $conn->query($sql);

    if (!$result) {
        return 0;
    }

    $row = $result->fetch_assoc();

    return (int) ($row['total'] ?? 0);
}

$dashboard = [
    'orders_pending' => dashboardCount(
        $conn,
        "SELECT COUNT(*) AS total FROM orders WHERE status = 'Pending'"
    ),
    'products_active' => dashboardCount(
        $conn,
        "SELECT COUNT(*) AS total FROM products2 WHERE deleted_at IS NULL"
    ),
    'comments_pending' => dashboardCount(
        $conn,
        "SELECT COUNT(*) AS total FROM comments WHERE status = 'pending'"
    ),
    'messages_unread' => dashboardCount(
        $conn,
        "SELECT COUNT(*) AS total FROM contact_messages WHERE status = 'Unread'"
    ),
];

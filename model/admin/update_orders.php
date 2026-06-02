<?php

require_once FILE_SECURITY_HELPER;
require_admin();
csrf_verify_or_die();

include_once FILE_CONNECT;

$orderId = (int) ($_POST['order_id'] ?? 0);
$newStatus = $_POST['status'] ?? '';

$allowedStatuses = [
    'Pending',
    'Processing',
    'Shipped',
    'Completed',
    'Cancelled'
];

if ($orderId <= 0 || !in_array($newStatus, $allowedStatuses, true)) {
    flash_set('error', 'Neispravan zahtjev za narudžbu.');
    header("Location: index.php?page=adminPanel&view=orders");
    exit;
}

$conn->begin_transaction();

try {
    $stmt = $conn->prepare("
        SELECT status, stock_reduced
        FROM orders
        WHERE id = ?
        LIMIT 1
    ");

    $stmt->bind_param("i", $orderId);
    $stmt->execute();

    $order = $stmt->get_result()->fetch_assoc();

    if (!$order) {
        throw new Exception('Narudžba nije pronađena.');
    }

    $shouldReduceStock =
        (int) $order['stock_reduced'] === 0 &&
        $newStatus === 'Shipped';

    if ($shouldReduceStock) {
        $stmt = $conn->prepare("
            SELECT product_id, size, quantity
            FROM order_items
            WHERE order_id = ?
        ");

        $stmt->bind_param("i", $orderId);
        $stmt->execute();

        $items = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        foreach ($items as $item) {
            $stmt = $conn->prepare("
                UPDATE product_sizes
                SET stock = stock - ?
                WHERE product_id = ?
                AND size = ?
                AND stock >= ?
            ");

            $stmt->bind_param(
                "iisi",
                $item['quantity'],
                $item['product_id'],
                $item['size'],
                $item['quantity']
            );

            $stmt->execute();

            if ($stmt->affected_rows === 0) {
                throw new Exception('Nema dovoljno zaliha za proizvod ID: ' . $item['product_id']);
            }
        }

        $stmt = $conn->prepare("
            UPDATE orders
            SET status = ?, stock_reduced = 1
            WHERE id = ?
        ");

        $stmt->bind_param("si", $newStatus, $orderId);
        $stmt->execute();
    } else {
        $stmt = $conn->prepare("
            UPDATE orders
            SET status = ?
            WHERE id = ?
        ");

        $stmt->bind_param("si", $newStatus, $orderId);
        $stmt->execute();
    }

    $conn->commit();

    flash_set('success', 'Status narudžbe je ažuriran.');
    header("Location: index.php?page=adminPanel&view=order_info&id=" . $orderId);
    exit;
} catch (Exception $e) {
    $conn->rollback();

    flash_set('error', $e->getMessage());
    header("Location: index.php?page=adminPanel&view=order_info&id=" . $orderId);
    exit;
}

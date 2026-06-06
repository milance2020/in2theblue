<?php

header('Content-Type: application/json');
session_start();

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

include FILE_CONNECT;
require_once FILE_CART_HELPER;

$action = $_GET['action'] ?? '';

try {

    // Sve cart akcije primaju JSON body iz JavaScripta.
    $data = json_decode(file_get_contents("php://input"), true) ?? [];

    $id   = (int)($data['id'] ?? 0);
    $size = trim($data['size'] ?? '');
    $qty  = (int)($data['qty'] ?? 1);

    // =========================
    // ADD
    // =========================
    if ($action === 'add') {

        $res = cartAdd($conn, $id, $size, $qty);

        echo json_encode($res);
        exit;
    }

    // =========================
    // GET
    // =========================
    if ($action === 'get') {
        // GET vraca cijelo stanje korpe za mini cart i cart stranicu.

        $items = cartGet($conn);

        echo json_encode([
            'success' => true,
            'count'   => cartCount(),
            'total'   => cartTotal($conn),   
            'items'   => $items
        ]);

        exit;
    }

    // =========================
    // REMOVE
    // =========================
    if ($action === 'remove') {

        $res = cartRemove($conn, $id, $size);

        echo json_encode($res);
        exit;
    }

    // =========================
    // INCREASE
    // =========================
    if ($action === 'increase') {

        $res = cartIncrease($conn, $id, $size);

        echo json_encode($res);
        exit;
    }

    // =========================
    // DECREASE
    // =========================
    if ($action === 'decrease') {

        $res = cartDecrease($conn, $id, $size);

        echo json_encode($res);
        exit;
    }

    // =========================
    // INVALID ACTION
    // =========================
    echo json_encode([
        'success' => false,
        'message' => 'Invalid action',
        'count' => cartCount(),
        'items' => []
    ]);

} catch (Exception $e) {

    echo json_encode([
        'success' => false,
        'message' => $e->getMessage(),
        'count' => cartCount(),
        'items' => []
    ]);
}

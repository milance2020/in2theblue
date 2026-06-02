<?php

$_output['html_model'] = 'adminPanel';
$_output['layout'] = 'admin';
$_output['view'] = 'admin/dashboard';

require_once FILE_SEO_HELPER;
setSEO('adminPanel');


// =========================================================
// AUTH
// =========================================================
if (
    !isset($_SESSION['ulogovan']) ||
    $_SESSION['ulogovan'] === USER_LEVEL_ANONYMOUS
) {

    header('Location: ' . appUrl('login'));
    exit;
}

if (($_SESSION['role'] ?? '') !== 'admin') {

    http_response_code(403);

    $_output['view'] = 'errors/403';

    return;
}


// =========================================================
// VIEW ROUTES
// =========================================================
$views = [
    ''  => [
        'view'  => 'admin/dashboard',
        'model' => 'dashboard.php',
    ],

    'dashboard'  => [
        'view'  => 'admin/dashboard',
        'model' => 'dashboard.php',
    ],

    'view' => [
        'view'  => 'admin/products-list',
        'model' => 'view_products.php',
    ],

    'insert' => [
        'view' => 'admin/product-insert',
    ],

    'update' => [
        'view'  => 'admin/product-update',
        'model' => 'update_products.php',
    ],

    'orders' => [
        'view'  => 'admin/orders-list',
        'model' => 'view_orders.php',
    ],

    'viewMessages' => [
        'view'  => 'admin/messages',
        'model' => 'view_messages.php',
    ],
    'contact_message_info' => [
        'view'  => 'admin/contact-message-info',
        'model' => 'model-contact-message-info.php',
    ],

    'order_info' => [
        'view'  => 'admin/order-detail',
        'model' => 'order_info.php',
    ],

    'insertUsers' => [
        'view' => 'admin/user-insert',
    ],

    'insertNews' => [
        'view' => 'admin/news-insert',
    ],

    'viewNews' => [
        'view'  => 'admin/news-list',
        'model' => 'view_news.php',
    ],

    'updateNews' => [
        'view'  => 'admin/news-update',
        'model' => 'update_news.php',
    ],
    'viewComments' => [
        'view'  => 'admin/comments',
        'model' => 'view_comments.php',
    ],
];


// =========================================================
// ACTION ROUTES
// =========================================================

$actions = [

    'insert' => 'insert.php',

    'update' => 'update.php',

    'delete' => 'delete_product.php',

    'insertUsers' => 'insert_users_bp.php',

    'insertNews' => 'insert_news.php',

    'updateNews' => 'update_news_push.php',

    'deleteNews' => 'delete_news.php',

    'update_order_status' => 'update_orders.php',
];


// =========================================================
// LOAD VIEW
// =========================================================
if (isset($views[$_view])) {

    $config = $views[$_view];

    $_output['view'] = $config['view'];

    if (!empty($config['model'])) {

        include DIR_ADMIN_MODEL . $config['model'];
    }

} else {

    http_response_code(404);

    $_output['view'] = 'errors/404';
}


// =========================================================
// LOAD ACTION
// =========================================================
if (!empty($_action) && isset($actions[$_action])) {

    include DIR_ADMIN_MODEL . $actions[$_action];
}

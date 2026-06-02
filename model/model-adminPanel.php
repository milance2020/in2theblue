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

if (!isModerator()) {
    http_response_code(403);

    $_output['html_model'] = 'error';
    $_output['layout'] = '';
    $_output['view'] = 'errors/403';

    return;
}


// =========================================================
// ROUTES
// =========================================================
$views = [
    '' => [
        'view' => 'admin/dashboard',
        'model' => 'dashboard.php',
        'roles' => ['admin', 'moderator'],
    ],
    'dashboard' => [
        'view' => 'admin/dashboard',
        'model' => 'dashboard.php',
        'roles' => ['admin', 'moderator'],
    ],
    'view' => [
        'view' => 'admin/products-list',
        'model' => 'view_products.php',
        'roles' => ['admin', 'moderator'],
    ],
    'insert' => [
        'view' => 'admin/product-insert',
        'roles' => ['admin'],
    ],
    'update' => [
        'view' => 'admin/product-update',
        'model' => 'update_products.php',
        'roles' => ['admin'],
    ],
    'orders' => [
        'view' => 'admin/orders-list',
        'model' => 'view_orders.php',
        'roles' => ['admin', 'moderator'],
    ],
    'order_info' => [
        'view' => 'admin/order-detail',
        'model' => 'order_info.php',
        'roles' => ['admin', 'moderator'],
    ],
    'viewMessages' => [
        'view' => 'admin/messages',
        'model' => 'view_messages.php',
        'roles' => ['admin', 'moderator'],
    ],
    'contact_message_info' => [
        'view' => 'admin/contact-message-info',
        'model' => 'model-contact-message-info.php',
        'roles' => ['admin', 'moderator'],
    ],
    'viewComments' => [
        'view' => 'admin/comments',
        'model' => 'view_comments.php',
        'roles' => ['admin', 'moderator'],
    ],
    'insertNews' => [
        'view' => 'admin/news-insert',
        'roles' => ['admin', 'moderator'],
    ],
    'viewNews' => [
        'view' => 'admin/news-list',
        'model' => 'view_news.php',
        'roles' => ['admin', 'moderator'],
    ],
    'updateNews' => [
        'view' => 'admin/news-update',
        'model' => 'update_news.php',
        'roles' => ['admin', 'moderator'],
    ],
    'siteContent' => [
        'view' => 'admin/content-edit',
        'model' => 'content_edit.php',
        'roles' => ['admin', 'moderator'],
    ],
    'insertUsers' => [
        'view' => 'admin/user-insert',
        'roles' => ['admin'],
    ],
];

$actions = [
    'insert' => [
        'model' => 'insert.php',
        'roles' => ['admin'],
    ],
    'update' => [
        'model' => 'update.php',
        'roles' => ['admin'],
    ],
    'delete' => [
        'model' => 'delete_product.php',
        'roles' => ['admin'],
    ],
    'insertUsers' => [
        'model' => 'insert_users_bp.php',
        'roles' => ['admin'],
    ],
    'insertNews' => [
        'model' => 'insert_news.php',
        'roles' => ['admin', 'moderator'],
    ],
    'updateNews' => [
        'model' => 'update_news_push.php',
        'roles' => ['admin', 'moderator'],
    ],
    'deleteNews' => [
        'model' => 'delete_news.php',
        'roles' => ['admin'],
    ],
    'update_order_status' => [
        'model' => 'update_orders.php',
        'roles' => ['admin', 'moderator'],
    ],
    'updateSiteContent' => [
        'model' => 'update_content.php',
        'roles' => ['admin', 'moderator'],
    ],
];


// =========================================================
// LOAD ACTION
// =========================================================
if (!empty($_action) && isset($actions[$_action])) {
    $actionConfig = $actions[$_action];

    if (!roleCanAccess($actionConfig['roles'])) {
        http_response_code(403);
        $_output['view'] = 'errors/403';
        return;
    }

    include DIR_ADMIN_MODEL . $actionConfig['model'];
}


// =========================================================
// LOAD VIEW
// =========================================================
if (isset($views[$_view])) {
    $config = $views[$_view];

    if (!roleCanAccess($config['roles'])) {
        http_response_code(403);
        $_output['view'] = 'errors/403';
        return;
    }

    $_output['view'] = $config['view'];

    if (!empty($config['model'])) {
        include DIR_ADMIN_MODEL . $config['model'];
    }
} else {
    http_response_code(404);
    $_output['view'] = 'errors/404';
}

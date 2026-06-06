<?php

// =========================================================
// CLEAN URL ROUTER
// =========================================================

$url = trim($_GET['url'] ?? '', '/');

if ($url === '') {
    // Prazan URL ide na normalni index flow.
    return;
}

$segments = explode('/', $url);

$firstSegment = $segments[0] ?? '';


// =========================================================
// SIMPLE CLEAN ROUTES
// =========================================================

$cleanRoutes = [
    'shop'        => 'explore',
    'index'       => 'in2thebar',
    'in2theshop'  => 'shop',
    'cart'        => 'cart-checkout',
    'checkout'    => 'checkout',
    'login'       => 'login',
    'order'       => 'order',
    'news'        => 'news',
    'contact'     => 'contact',
    'register'    => 'register',
];

if (isset($cleanRoutes[$firstSegment])) {
    // Clean URL pretvaramo u stari interni page parametar.
    $_GET['page'] = $cleanRoutes[$firstSegment];
}


// =========================================================
// LOGOUT
// =========================================================

if ($firstSegment === 'logout') {
    $_GET['page'] = 'login';
    $_GET['action'] = 'logout';

    return;
}


// =========================================================
// ORDER SUCCESS
// /order-success/15
// =========================================================

if (
    $firstSegment === 'order-success' &&
    isset($segments[1])
) {
    $_GET['page'] = 'order-success';
    $_GET['order_id'] = (int) $segments[1];

    return;
}


// =========================================================
// PRODUCT
// /shop/category/product
// =========================================================

if (
    $firstSegment === 'shop' &&
    isset($segments[1], $segments[2])
) {
    $_GET['page'] = 'product';
    $_GET['category_slug'] = $segments[1];
    $_GET['product_slug'] = $segments[2];

    return;
}


// =========================================================
// CATEGORY
// /shop/category
// =========================================================

if (
    $firstSegment === 'shop' &&
    isset($segments[1]) &&
    !isset($segments[2])
) {
    $_GET['page'] = 'explore';
    $_GET['category'] = $segments[1];

    return;
}

// =========================================================
// NEWS ARTICLE
// /news/15
// /news/15-naslov-vijesti
// =========================================================

if (
    $firstSegment === 'news' &&
    isset($segments[1])
) {
    // ID ostaje broj, slug je samo za citljiv URL.
    $_GET['page'] = 'news';
    $_GET['id'] = (int) $segments[1];

    return;
}

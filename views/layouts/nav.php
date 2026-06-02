<!doctype html>
<html>

<head>
    <meta charset="UTF-8">
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title><?= e($_output['meta_title'] ?? 'Shop') ?></title>

    <meta name="description" content="<?= e($_output['meta_description'] ?? '') ?>">
    <meta name="robots" content="<?= e($_output['meta_robots'] ?? 'index, follow') ?>">

    <?php if (!empty($_output['canonical'])): ?>
        <link rel="canonical" href="<?= e($_output['canonical']) ?>">
    <?php endif; ?>

    <link rel="icon" type="image/png" sizes="16x16" href="<?= assetUrl('images/favicon/favicon-16x16.png') ?>">
    <link rel="icon" type="image/png" sizes="32x32" href="<?= assetUrl('images/favicon/favicon-32x32.png') ?>">
    <link rel="icon" type="image/png" sizes="48x48" href="<?= assetUrl('images/favicon/favicon-48x48.png') ?>">
    <link rel="apple-touch-icon" sizes="180x180" href="<?= assetUrl('images/favicon/apple-touch-icon.png') ?>">
    <link rel="shortcut icon" href="<?= assetUrl('images/favicon/favicon.ico') ?>">

    <link rel="stylesheet" href="<?= assetUrl('css/public/style.css') ?>">
    <link rel="stylesheet" href="<?= assetUrl('css/public/forms.css') ?>">
    <link rel="stylesheet" href="<?= assetUrl('css/public/tables.css') ?>">
    <link rel="stylesheet" href="<?= assetUrl('css/public/news.css') ?>">
    <link rel="stylesheet" href="<?= assetUrl('css/public/shop.css') ?>">

    <link rel="stylesheet" href="<?= assetUrl('css/admin/adminPanel.css') ?>">
    <link rel="stylesheet" href="<?= assetUrl('css/admin/order_info.css') ?>">

    <script src="<?= URL_ASSETS_JS_SHOP ?>cart.service.js"></script>
    <script src="<?= URL_ASSETS_JS_SHOP ?>cart.ui.js"></script>
</head>

<body
    data-logged-in="<?= !empty($_SESSION['ulogovan']) ? '1' : '0' ?>"
    data-api-cart-url="<?= e(URL_API_CART) ?>"
>

    <?php
    $page = $_GET['page'] ?? '';
    $isLoggedIn = !empty($_SESSION['ulogovan']);
    $userRole = $_SESSION['role'] ?? '';
    $isShopPage = $page === 'shop' || $page === 'explore' || $page === 'product';
    ?>

<nav class="site-nav">

    <a href="<?= appUrl('in2theshop') ?>" class="nav-logo">
        <?php if ($isShopPage): ?>
            <span class="logo-mark shop-logo"></span>
        <?php else: ?>
            <span class="logo-mark bar-logo"></span>
        <?php endif; ?>
    </a>

    <button class="nav-toggle" type="button" aria-label="Open menu">
        <span></span>
        <span></span>
        <span></span>
    </button>

    <div class="nav-menu">
        <div class="nav-main-links">
            <a href="<?= appUrl('in2theshop') ?>">In2TheShop</a>
            <a href="<?= appUrl('in2thebar') ?>">In2TheBar</a>
            <a href="<?= appUrl('news') ?>">Vijesti</a>

            <?php if ($isLoggedIn && in_array($userRole, ['admin', 'moderator'], true)): ?>
                <a href="<?= pageUrl('adminPanel') ?>">Admin Panel</a>
            <?php endif; ?>
        </div>

        <div class="nav-user-links">
            <?php if ($isLoggedIn): ?>
                <span class="nav-username">
                    <?= e($_SESSION['username']) ?>
                </span>

                <a href="<?= logoutUrl() ?>" class="logout-btn">
                    Log out
                </a>
            <?php else: ?>
                <a href="<?= appUrl('login') ?>">
                    Log in
                </a>
            <?php endif; ?>

            <?php if ($page === 'explore' || $page === 'product'): ?>
                <div class="cart-wrapper">
                    <a href="#" class="cart-icon">
                        Korpa
                        <span class="cart-count">0</span>
                    </a>

                    <div class="cart-dropdown" id="cart-dropdown">
                        <div class="cart-items"></div>

                        <div class="cart-footer">
                            <a href="<?= appUrl('cart') ?>">
                                Pregledaj korpu
                            </a>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

</nav>

<script src="<?= assetUrl('js/layout/nav.js') ?>"></script>

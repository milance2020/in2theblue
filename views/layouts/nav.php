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

    <link rel="stylesheet" href="<?= assetUrl('css/public/style.css') ?>">
    <link rel="stylesheet" href="<?= assetUrl('css/public/forms.css') ?>">
    <link rel="stylesheet" href="<?= assetUrl('css/public/tables.css') ?>">
    <link rel="stylesheet" href="<?= assetUrl('css/public/news.css') ?>">
    <link rel="stylesheet" href="<?= assetUrl('css/public/shop.css') ?>">

    <link rel="stylesheet" href="<?= assetUrl('css/admin/adminPanel.css') ?>">
    <link rel="stylesheet" href="<?= assetUrl('css/admin/order_info.css') ?>">

    <script>window.APP_URLS = <?= json_encode(['apiCart' => URL_API_CART]) ?>;</script>
    <script src="<?= URL_ASSETS_JS_SHOP ?>cart.service.js"></script>
    <script src="<?= URL_ASSETS_JS_SHOP ?>cart.ui.js"></script>
</head>

<body data-logged-in="<?= !empty($_SESSION['ulogovan']) ? '1' : '0' ?>">

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

            <?php if ($isLoggedIn && $userRole === 'admin'): ?>
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
                        Cart
                        <span class="cart-count">0</span>
                    </a>

                    <div class="cart-dropdown" id="cart-dropdown">
                        <div class="cart-items"></div>

                        <div class="cart-footer">
                            <a href="<?= appUrl('cart') ?>">
                                View cart
                            </a>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

</nav>

<script>
const nav = document.querySelector('.site-nav');
const navToggle = document.querySelector('.nav-toggle');

if (nav && navToggle) {
    navToggle.addEventListener('click', function () {
        nav.classList.toggle('open');
    });
}

const cartWrapper = document.querySelector('.cart-wrapper');

if (cartWrapper) {
    cartWrapper.addEventListener('click', function (e) {
        if (window.innerWidth > 900) {
            return;
        }

        const cartIcon = e.target.closest('.cart-icon');

        if (!cartIcon) {
            return;
        }

        e.preventDefault();
        cartWrapper.classList.toggle('open');
    });
}
</script>

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

<?php
$page = $_GET['page'] ?? 'index';

if ($page === 'index' || $page === 'shop') {
    require_once FILE_SITE_CONTENT_HELPER;
    include_once FILE_CONNECT;
}

if ($page === 'index') {
    $homeHeroContent = loadHomeHeroContent($conn);
} elseif ($page === 'shop') {
    $shopHeroContent = loadShopHeroContent($conn);
}
?>

<?php if ($page == 'index'): ?>
    <?php $slider = 'slider-bar.js'; ?>

    <div id="header">
        <div id="slider">
            <div id="logo-index"></div>
            <div class="hero-content">

                <span class="hero-tag">
                    <?= e($homeHeroContent['home_hero_tag']) ?>
                </span>

                <h1>
                    <?= e($homeHeroContent['home_hero_title']) ?>
                </h1>

                <p>
                    <?= e($homeHeroContent['home_hero_text']) ?>
                </p>

                <div class="hero-buttons">

                    <a href="<?= appUrl('in2thebar') ?>" class="hero-btn primary">
                        <?= e($homeHeroContent['home_hero_primary_label']) ?>
                    </a>

                    <a href="<?= shopUrl() ?>" class="hero-btn secondary">
                        <?= e($homeHeroContent['home_hero_secondary_label']) ?>
                    </a>

                </div>

            </div>
        </div>
    </div>

<?php elseif ($page == 'shop'): ?>
    <?php $slider = 'slider-shop.js'; ?>

    <div id="header">
        <div id="slider">
            <div class="hero-content">

                <span class="hero-tag">
                    <?= e($shopHeroContent['shop_hero_tag']) ?>
                </span>

                <h1>
                    <?= e($shopHeroContent['shop_hero_title']) ?>
                </h1>

                <p>
                    <?= e($shopHeroContent['shop_hero_text']) ?>
                </p>

                <div class="hero-buttons">

                    <a href="<?= shopUrl() ?>" class="hero-btn primary">
                        <?= e($shopHeroContent['shop_hero_primary_label']) ?>
                    </a>

                    <a href="<?= appUrl('in2thebar') ?>" class="hero-btn secondary">
                        <?= e($shopHeroContent['shop_hero_secondary_label']) ?>
                    </a>

                </div>

            </div>
        </div>
    </div>

<?php endif; ?>

<?php if (!empty($slider)): ?>
    <script src="<?= URL_ASSETS_JS_SLIDERS . $slider ?>"></script>
<?php endif; ?>

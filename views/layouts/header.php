<?php
$page = $_GET['page'] ?? 'index';

if ($page === 'index') {
    require_once FILE_SITE_CONTENT_HELPER;
    include_once FILE_CONNECT;

    $homeHeroContent = loadHomeHeroContent($conn);
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
                    MORE - OPREMA - LIFESTYLE
                </span>

                <h1>
                    Istražite obalni lifestyle
                </h1>

                <p>
                    Premium nautička oprema, aktivan život uz more,
                    bike i SUP rental te proizvodi inspirirani
                    morem i avanturom.
                </p>

                <div class="hero-buttons">

                    <a href="<?= shopUrl() ?>" class="hero-btn primary">
                        Istražite Shop
                    </a>

                    <a href="<?= appUrl('in2thebar') ?>" class="hero-btn secondary">
                        Posjeti Bar
                    </a>

                </div>

            </div>
        </div>
    </div>

<?php endif; ?>

<?php if (!empty($slider)): ?>
    <script src="<?= URL_ASSETS_JS_SLIDERS . $slider ?>"></script>
<?php endif; ?>

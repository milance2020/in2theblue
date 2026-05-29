<?php
$page = $_GET['page'] ?? 'index';
?>

<?php if ($page == 'index'): ?>
    <?php $slider = 'slider-bar.js'; ?>

    <div id="header">
        <div id="slider">
            <div id="logo-index"></div>
            <div class="hero-content">

                <span class="hero-tag">
                    SUNSET - KOKTELI - GLAZBA
                </span>

                <h1>
                    Mjesto gdje ljeto ostaje duze
                </h1>

                <p>
                    Kokteli, lokalna hrana, zalasci sunca i opustena atmosfera
                    inspirirana morem i mediteranskim nacinom zivota.
                </p>

                <div class="hero-buttons">

                    <a href="<?= appUrl('in2thebar') ?>" class="hero-btn primary">
                        Istrazite Bar
                    </a>

                    <a href="<?= shopUrl() ?>" class="hero-btn secondary">
                        Posjeti Shop
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
                    Istrazite obalni lifestyle
                </h1>

                <p>
                    Premium nauticka oprema, aktivan zivot uz more,
                    bike i SUP rental te proizvodi inspirirani
                    morem i avanturom.
                </p>

                <div class="hero-buttons">

                    <a href="<?= shopUrl() ?>" class="hero-btn primary">
                        Istrazite Shop
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

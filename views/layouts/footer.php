<?php
require_once FILE_SITE_CONTENT_HELPER;
include_once FILE_CONNECT;

$footerContent = loadFooterContent($conn);
?>

<footer id="footer">

    <div class="footer-container">

        <div class="footer-section footer-brand">

            <h3><?= e($footerContent['footer_brand_title']) ?></h3>

            <p>
                <?= e($footerContent['footer_brand_text']) ?>
            </p>

        </div>

        <div class="footer-section">

            <h4><?= e($footerContent['footer_navigation_title']) ?></h4>

            <ul class="footer-links">
                <li>
                    <a href="<?= appUrl('') ?>">
                        Početna
                    </a>
                </li>

                <li>
                    <a href="<?= appUrl('in2thebar') ?>">
                        IN2THEBAR
                    </a>
                </li>

                <li>
                    <a href="<?= appUrl('in2theshop') ?>">
                        IN2THESHOP
                    </a>
                </li>

                <li>
                    <a href="<?= appUrl('contact') ?>">
                        Kontakt
                    </a>
                </li>
            </ul>

        </div>

        <div class="footer-section">

            <h4><?= e($footerContent['footer_contact_title']) ?></h4>

            <p><?= e($footerContent['footer_address']) ?></p>

            <p>
                <?= e($footerContent['footer_phone']) ?>
            </p>

            <p>
                <?= e($footerContent['footer_email']) ?>
            </p>

        </div>

        <div class="footer-section">

            <h4><?= e($footerContent['footer_social_title']) ?></h4>

            <div class="footer-socials">

                <a href="<?= e($footerContent['footer_instagram_url']) ?>">
                    <?= e($footerContent['footer_instagram_label']) ?>
                </a>

                <a href="<?= e($footerContent['footer_facebook_url']) ?>">
                    <?= e($footerContent['footer_facebook_label']) ?>
                </a>

                <a href="<?= e($footerContent['footer_tiktok_url']) ?>">
                    <?= e($footerContent['footer_tiktok_label']) ?>
                </a>

            </div>

            <div class="footer-hours">

                <p><?= e($footerContent['footer_hours_title']) ?></p>

                <strong>
                    <?= e($footerContent['footer_hours_text']) ?>
                </strong>

            </div>

        </div>

    </div>

    <div class="footer-bottom">

        <p>
            <?= e($footerContent['footer_bottom_text']) ?>
        </p>

    </div>

</footer>

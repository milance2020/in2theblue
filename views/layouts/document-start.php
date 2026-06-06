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

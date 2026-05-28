<?php

// =========================================================
// ROUTER FOR URL MANIPULATION
// =========================================================
include __DIR__ . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'router.php';

// =========================================================
// REQUEST STATE
// =========================================================

$_action = $_GET['action'] ?? '';
$_id     = $_GET['id'] ?? 0;
$_page   = $_GET['page'] ?? '';
$_view   = $_GET['view'] ?? '';
$slug    = $_GET['slug'] ?? '';


// =========================================================
// OUTPUT STATE
// =========================================================

$_output = [
    // VIEW
    'view' => '',
    'layout' => '',
    'html_model' => '',

    // FLASH / UI
    'errors' => [],
    'messages' => [],

    // DATA
    'data' => [],

    // SHOP STATE
    'cart_count' => 0,

    // SEO
    'meta_title' => '',
    'meta_description' => '',
    'meta_keywords' => '',
    'breadcrumbs_enabled' => false,
    'breadcrumbs' => [],

    // Later useful
    'canonical_url' => '',
];


// =========================================================
// SESSION
// =========================================================

session_start();

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}


// =========================================================
// BOOTSTRAP
// =========================================================

require_once __DIR__ . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'constants.php';

require_once FILE_URL_HELPER;

require_once DIR_CORE . 'view.php';

require_once FILE_PUBLIC_CONTROLLER;


// =========================================================
// RENDER
// =========================================================

include $model_filename;

include FILE_LAYOUT_NAV;
include FILE_LAYOUT_HEADER;
include FILE_LAYOUT_BODY;
include FILE_LAYOUT_FOOTER;
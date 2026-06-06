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
    // Modeli pune ovaj niz, layout ga kasnije koristi za render i SEO.
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

    // Canonical URL za SEO helper/layout.
    'canonical' => '',
];


// =========================================================
// SESSION
// =========================================================

session_start();

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}



require_once __DIR__ . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'constants.php';

require_once FILE_SECURITY_HELPER;

require_once FILE_URL_HELPER;

require_once DIR_CORE . 'view.php';

require_once FILE_PUBLIC_CONTROLLER;


// =========================================================
// RENDER
// =========================================================

include $model_filename;

// Render ide uvijek istim redom: document start, nav, hero, body, footer.
include FILE_LAYOUT_DOCUMENT_START;
include FILE_LAYOUT_NAV;
include FILE_LAYOUT_HERO;
include FILE_LAYOUT_BODY;
include FILE_LAYOUT_FOOTER;

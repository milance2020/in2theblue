<?php

define('DIR_ROOT', dirname(__DIR__) . DIRECTORY_SEPARATOR);

define('DIR_CORE', DIR_ROOT . 'core' . DIRECTORY_SEPARATOR);
define('DIR_CONTROLERS', DIR_ROOT . 'controlers' . DIRECTORY_SEPARATOR);
define('DIR_PUBLIC_CONTROLERS', DIR_CONTROLERS . 'public' . DIRECTORY_SEPARATOR);
define('DIR_VIEWS', DIR_ROOT . 'views' . DIRECTORY_SEPARATOR);
define('DIR_VIEW_LAYOUTS', DIR_VIEWS . 'layouts' . DIRECTORY_SEPARATOR);
define('DIR_VIEW_PAGES', DIR_VIEWS . 'pages' . DIRECTORY_SEPARATOR);
define('DIR_VIEW_ADMIN', DIR_VIEWS . 'admin' . DIRECTORY_SEPARATOR);
define('DIR_VIEW_PARTIALS', DIR_VIEWS . 'partials' . DIRECTORY_SEPARATOR);
/** @deprecated Use view_include() / view_path() instead */
define('DIR_PUBLIC_VIEWS', DIR_VIEWS);
define('DIR_MODEL', DIR_ROOT . 'model' . DIRECTORY_SEPARATOR);
define('DIR_ADMIN_MODEL', DIR_MODEL . 'admin' . DIRECTORY_SEPARATOR);
define('DIR_PUBLIC_MODEL', DIR_MODEL . 'public' . DIRECTORY_SEPARATOR);
define('DIR_ADMIN_VIEWS', DIR_VIEWS . 'admin' . DIRECTORY_SEPARATOR);
define('DIR_ADMIN_CONTROLERS', DIR_CONTROLERS . 'admin' . DIRECTORY_SEPARATOR);
define('DIR_API', DIR_ROOT . 'api' . DIRECTORY_SEPARATOR);
define('DIR_ASSETS', DIR_ROOT . 'assets' . DIRECTORY_SEPARATOR);
define('DIR_MODEL_API', DIR_MODEL . 'api' . DIRECTORY_SEPARATOR);
define('DIR_MODEL_HELPERS', DIR_MODEL . 'helpers' . DIRECTORY_SEPARATOR);
define('DIR_ADMIN_INCLUDES', DIR_ADMIN_MODEL . 'includes' . DIRECTORY_SEPARATOR);

define('DIR_ASSETS_IMAGES_SHOP', DIR_ASSETS . 'images' . DIRECTORY_SEPARATOR . 'images_shop' . DIRECTORY_SEPARATOR . 'products' . DIRECTORY_SEPARATOR);
define('DIR_ASSETS_IMAGES_NEWS', DIR_ASSETS . 'images' . DIRECTORY_SEPARATOR . 'images_news' . DIRECTORY_SEPARATOR);
define('DIR_ASSETS_IMAGES_BAR', DIR_ASSETS . 'images' . DIRECTORY_SEPARATOR . 'images_bar' . DIRECTORY_SEPARATOR);
define('DIR_ASSETS_IMAGES_BAR_CARDS', DIR_ASSETS_IMAGES_BAR . 'cards' . DIRECTORY_SEPARATOR);

define('URL_BASE', '/v5/');
define('URL_ASSETS', URL_BASE . 'assets/');
define('URL_INDEX', URL_BASE . 'index.php');
define('URL_API_CART', URL_BASE . 'api/cart.php');

define('URL_ASSETS_CSS_PUBLIC', URL_ASSETS . 'css/public/');
define('URL_ASSETS_CSS_ADMIN', URL_ASSETS . 'css/admin/');
define('URL_ASSETS_JS', URL_ASSETS . 'js/');
define('URL_ASSETS_JS_SHOP', URL_ASSETS_JS . 'shop/shop_god/');
define('URL_ASSETS_JS_SLIDERS', URL_ASSETS_JS . 'sliders/');
define('URL_ASSETS_JS_ADMIN_COMMENTS', URL_ASSETS_JS . 'admin/comments/');
define('URL_ASSETS_IMAGES_BAR', URL_ASSETS . 'images/images_bar/');
define('URL_ASSETS_IMAGES_BAR_CARDS', URL_ASSETS_IMAGES_BAR . 'cards/');
define('URL_ASSETS_IMAGES_NEWS', URL_ASSETS . 'images/images_news/');
define('URL_ASSETS_IMAGES_SHOP', URL_ASSETS . 'images/images_shop/products/');

define('FILE_CONSTANTS', DIR_CORE . 'constants.php');
define('FILE_SECURITY_HELPER', DIR_CORE . 'security.php');
define('FILE_PUBLIC_CONTROLLER', DIR_CONTROLERS . 'publicController.php');
define('FILE_CONNECT', DIR_ADMIN_INCLUDES . 'connect.php');
define('FILE_SEO_HELPER', DIR_MODEL_HELPERS . 'seo.php');
define('FILE_CART_HELPER', DIR_MODEL_HELPERS . 'cart.php');
define('FILE_COMMENT_MODERATION',DIR_MODEL_HELPERS . 'commentModeration.php');
define('FILE_REPORT_MODERATION',DIR_MODEL_HELPERS . 'reportService.php');
define('FILE_URL_HELPER', DIR_MODEL_HELPERS . 'urlHelper.php');
define('FILE_PRODUCT_FUNCTIONS', DIR_MODEL_HELPERS . 'product-functions.php');
define('FILE_SITE_CONTENT_HELPER', DIR_MODEL_HELPERS . 'siteContent.php');

define('FILE_API_CART', DIR_MODEL_API . 'api-cart.php');
define('FILE_API_CART_ENDPOINT', DIR_API . 'cart.php');
define('FILE_LAYOUT_DOCUMENT_START', DIR_VIEW_LAYOUTS . 'document-start.php');
define('FILE_LAYOUT_NAV', DIR_VIEW_LAYOUTS . 'nav.php');
define('FILE_LAYOUT_HERO', DIR_VIEW_LAYOUTS . 'hero.php');
/** @deprecated Use FILE_LAYOUT_HERO */
define('FILE_LAYOUT_HEADER', FILE_LAYOUT_HERO);
define('FILE_LAYOUT_BODY', DIR_VIEW_LAYOUTS . 'body.php');
define('FILE_LAYOUT_FOOTER', DIR_VIEW_LAYOUTS . 'footer.php');
define('FILE_LAYOUT_MENU', DIR_VIEW_LAYOUTS . 'menu.php');
define('FILE_PARTIAL_NEWS_LATEST', DIR_VIEW_PARTIALS . 'news-latest.php');
/** @deprecated Use FILE_LAYOUT_* constants */
define('FILE_PAGE_DOCUMENT_START', FILE_LAYOUT_DOCUMENT_START);
define('FILE_PAGE_NAV', FILE_LAYOUT_NAV);
define('FILE_PAGE_HEADER', FILE_LAYOUT_HEADER);
define('FILE_PAGE_BODY', FILE_LAYOUT_BODY);
define('FILE_PAGE_FOOTER', FILE_LAYOUT_FOOTER);
define('FILE_MENI', FILE_LAYOUT_MENU);
define('FILE_VIEW_MODEL_NEWS_LATEST', FILE_PARTIAL_NEWS_LATEST);
define('FILE_MODEL_NEWS_LATEST', DIR_MODEL . 'model-news-latest.php');

define('USER_LEVEL_ANONYMOUS', 0);
define('USER_LEVEL_ADMIN', 1);
define('USER_LEVEL_USER', 2);
define('COMMENT_VISIBLE','visible');
define('COMMENT_PENDING','pending');
define('COMMENT_HIDDEN','hidden');

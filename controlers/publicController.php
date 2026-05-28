<?php

$_show_news = false;

switch ($_page) {
    case '':
    case 'index':
        $_page = 'index';
        $_show_news = true;

        break;

    case 'rooms':
        $_page = 'rooms';

        break;

    case 'shop':
        $_page = 'shop';


        break;
    case 'news':
        $_page = 'news';

        break;

    case 'explore':
        $_page = 'explore';

        break;

    case 'login':
        $_page = 'login';

        break;
    case 'adminPanel':
        $_page = 'adminPanel';


        break;


    case 'cart-checkout':
        $_page = 'cart-checkout';

        break;
    case 'order':
        $_page = 'order';

        break;
    case 'order-success':
        $_page = 'order-success';

        break;
    case 'product':
        $_page = 'product';
        break;
    case 'register':
        $_page = 'register';
        break;
    case 'contact':
        $_page = 'contact';
        break;
    default:
        $_page = 'error';
        break;
}


$model_filename = DIR_MODEL . 'model-' . $_page . '.php';

if (!file_exists($model_filename)) {
    $model_filename = DIR_MODEL . 'model-error404.php';
}

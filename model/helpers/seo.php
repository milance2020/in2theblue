<?php

if (!defined('URL_INDEX')) {
    require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'constants.php';
}

function addBreadcrumb(string $label, ?string $url = null): void
{
    global $_output;

    $_output['breadcrumbs'][] = [
        'label' => $label,
        'url'   => $url,
    ];
}

function addShopBreadcrumbs(): void
{
    addBreadcrumb('Početna', appUrl('in2theshop'));
    addBreadcrumb('In2TheShop', shopUrl());
}

function pageTitle(string $page): string
{
    return match ($page) {
        'explore'       => 'Shop',
        'product'       => 'Product',
        'cart-checkout' => 'Cart',
        'checkout'      => 'Checkout',
        'index'         => 'In2TheBar',
        'login'         => 'Login',
        'news'          => 'Vijesti',
        'shop'          => 'In2TheShop',
        default         => ucfirst($page),
    };
}

function genderLabel(string $gender): string
{
    return match (strtolower($gender)) {
        'male'   => 'Muško',
        'female' => 'Žensko',
        'unisex' => 'Unisex',
        default  => ucfirst($gender),
    };
}

function cleanMetaDescription(?string $text): string
{
    return substr(strip_tags($text ?? ''), 0, 160);
}

function setSEO(string $type, array $data = []): void
{
    global $_output;

    $_output['meta_robots'] = $data['robots'] ?? 'index, follow';
    $_output['breadcrumbs'] = [];

    $exploreUrl = shopUrl();
    $cartUrl = appUrl('cart');

    if ($type === 'shop') {
        $_output['meta_title'] = 'In2TheShop';
        $_output['meta_description'] = 'Online shop sa modernim proizvodima.';
        $_output['canonical'] = appUrl('in2theshop');

        addBreadcrumb('Početna', appUrl('in2thebar'));
        addBreadcrumb('In2TheShop');

        return;
    }

    if ($type === 'product') {
        $name = $data['name'] ?? 'Product';

        $_output['meta_title'] = $name . ' | Shop';
        $_output['meta_description'] = cleanMetaDescription($data['description'] ?? '');
        $_output['canonical'] = $data['url'] ?? pageUrl('product', [
            'id' => $data['id'] ?? ''
        ]);

        addShopBreadcrumbs();
        addBreadcrumb('Proizvodi', $exploreUrl);

        if (!empty($data['category']) && !empty($data['category_slug'])) {
            addBreadcrumb(
                $data['category'],
                appUrl('shop/' . $data['category_slug'])
            );
        }

        addBreadcrumb($name);

        return;
    }

    if ($type === 'explore') {
        $category = $data['category'] ?? '';
        $gender = $data['gender'] ?? '';
        $search = $data['search'] ?? '';
        $categoryLabel = $data['category_label'] ?? '';

        if ($categoryLabel === '' && $category !== '') {
            $categoryLabel = ucwords(str_replace(['-', '_'], ' ', $category));
        }

        $title = 'Shop';

        if ($search !== '') {
            $title = 'Pretraga: ' . $search . ' | Shop';
        } elseif ($categoryLabel !== '' && $gender !== '') {
            $title = $categoryLabel . ' - ' . genderLabel($gender) . ' | Shop';
        } elseif ($categoryLabel !== '') {
            $title = $categoryLabel . ' | Shop';
        } elseif ($gender !== '') {
            $title = genderLabel($gender) . ' | Shop';
        }

        $_output['meta_title'] = $title;
        $_output['meta_description'] = 'Istražite našu kolekciju proizvoda.';
        $_output['canonical'] = $data['url'] ?? $exploreUrl;

        addShopBreadcrumbs();

        $hasFilters = $category !== '' || $gender !== '' || $search !== '';

        addBreadcrumb('Proizvodi', $hasFilters ? $exploreUrl : null);

        if ($category !== '') {
            $isLast = $gender === '' && $search === '';

            addBreadcrumb(
                $categoryLabel,
                $isLast ? null : appUrl('shop/' . $category)
            );
        }

        if ($gender !== '') {
            $genderParams = array_filter([
                'category' => $category ?: null,
                'gender'   => $gender,
            ]);

            $isLast = $search === '';

            addBreadcrumb(
                genderLabel($gender),
                $isLast ? null : shopUrl($genderParams)
            );
        }

        if ($search !== '') {
            addBreadcrumb('Pretraga: ' . $search);
        }

        return;
    }

    if ($type === 'cart') {
        $_output['meta_title'] = 'Korpa | Shop';
        $_output['meta_description'] = '';
        $_output['meta_robots'] = 'noindex, nofollow';
        $_output['canonical'] = $cartUrl;

        addShopBreadcrumbs();
        addBreadcrumb('Korpa');

        return;
    }

    if ($type === 'checkout') {
        $_output['meta_title'] = 'Naručivanje | Shop';
        $_output['meta_description'] = '';
        $_output['meta_robots'] = 'noindex, nofollow';
        $_output['canonical'] = appUrl('order');

        addShopBreadcrumbs();
        addBreadcrumb('Korpa', $cartUrl);
        addBreadcrumb('Naručivanje');

        return;
    }

    if ($type === 'order_success') {
        $_output['meta_title'] = 'Narudžba uspješna | Shop';
        $_output['meta_description'] = 'Vaša narudžba je uspješno kreirana.';
        $_output['meta_robots'] = 'noindex, follow';

        addShopBreadcrumbs();
        addBreadcrumb('Korpa', $cartUrl);
        addBreadcrumb('Uspješno naručeno');

        return;
    }

    $_output['meta_title'] = pageTitle($type);
    $_output['meta_description'] = 'Online shop sa modernim proizvodima.';
}

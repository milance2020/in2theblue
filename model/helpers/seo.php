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
    addBreadcrumb('Pocetna', appUrl('in2theshop'));
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
        'male'   => 'Musko',
        'female' => 'Zensko',
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

    $exploreUrl = pageUrl('explore');
    $cartUrl = pageUrl('cart-checkout');

    if ($type === 'shop') {
        $_output['meta_title'] = 'In2TheShop';
        $_output['meta_description'] = 'Online shop sa modernim proizvodima.';
        $_output['canonical'] = pageUrl('shop');

        addBreadcrumb('Pocetna', pageUrl('index'));
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
                pageUrl('explore', [
                    'category' => $data['category_slug']
                ])
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
        $_output['meta_description'] = 'Istrazite nasu kolekciju proizvoda.';
        $_output['canonical'] = $data['url'] ?? $exploreUrl;

        addShopBreadcrumbs();

        $hasFilters = $category !== '' || $gender !== '' || $search !== '';

        addBreadcrumb('Proizvodi', $hasFilters ? $exploreUrl : null);

        if ($category !== '') {
            $isLast = $gender === '' && $search === '';

            addBreadcrumb(
                $categoryLabel,
                $isLast ? null : pageUrl('explore', ['category' => $category])
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
                $isLast ? null : pageUrl('explore', $genderParams)
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
        $_output['meta_title'] = 'Narucivanje | Shop';
        $_output['meta_description'] = '';
        $_output['meta_robots'] = 'noindex, nofollow';
        $_output['canonical'] = pageUrl('order');

        addShopBreadcrumbs();
        addBreadcrumb('Korpa', $cartUrl);
        addBreadcrumb('Narucivanje');

        return;
    }

    if ($type === 'order_success') {
        $_output['meta_title'] = 'Narudzba uspjesna | Shop';
        $_output['meta_description'] = 'Vasa narudzba je uspjesno kreirana.';
        $_output['meta_robots'] = 'noindex, follow';

        addShopBreadcrumbs();
        addBreadcrumb('Korpa', $cartUrl);
        addBreadcrumb('Uspjesno naruceno');

        return;
    }

    $_output['meta_title'] = pageTitle($type);
    $_output['meta_description'] = 'Online shop sa modernim proizvodima.';
}

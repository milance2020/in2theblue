<?php

function appUrl(string $path = ''): string
{
    return rtrim(URL_BASE, '/') . '/' . ltrim($path, '/');
}

function pageUrl(string $page, array $params = []): string
{
    // Public stranice dobijaju nice URL, admin moze ostati query URL.
    $cleanPaths = [
        'index' => 'in2thebar',
        'shop' => 'in2theshop',
        'explore' => 'shop',
        'cart-checkout' => 'cart',
        'order' => 'order',
        'login' => 'login',
        'register' => 'register',
        'contact' => 'contact',
        'news' => 'news',
    ];

    if (isset($cleanPaths[$page])) {
        $url = appUrl($cleanPaths[$page]);

        if (!empty($params)) {
            $cleanParams = array_filter(
                $params,
                fn($value) => $value !== null && $value !== ''
            );

            if (!empty($cleanParams)) {
                $url .= '?' . http_build_query($cleanParams);
            }
        }

        return $url;
    }

    $url = URL_INDEX . '?page=' . rawurlencode($page);

    foreach ($params as $key => $value) {
        if ($value === null || $value === '') {
            continue;
        }

        $url .= '&' . rawurlencode($key) . '=' . rawurlencode((string) $value);
    }

    return $url;
}

function productUrl(array $product): string
{
    if (!empty($product['category_slug']) && !empty($product['slug'])) {
        return appUrl(
            'shop/' . $product['category_slug'] . '/' . $product['slug']
        );
    }

    return pageUrl('product', ['id' => $product['id'] ?? null]);
}

function shopUrl(array $params = []): string
{
    $url = appUrl('shop');

    if (!empty($params)) {
        $url .= '?' . http_build_query($params);
    }

    return $url;
}

function categoryUrl(array $category): string
{
    return appUrl('shop/' . $category['slug']);
}

function assetUrl(string $path): string
{
    return appUrl('assets/' . ltrim($path, '/'));
}

function storedFileUrl(?string $path): string
{
    // Baza nekad cuva relativan path, nekad vec kompletan URL.
    $path = trim($path ?? '');

    if ($path === '') {
        return '';
    }

    if (preg_match('/^https?:\/\//', $path)) {
        return $path;
    }

    if (str_starts_with($path, URL_BASE)) {
        return $path;
    }

    return appUrl($path);
}

function logoutUrl(): string
{
    return appUrl('logout');
}

function orderSuccessUrl(int $orderId): string
{
    return appUrl(
        'order-success/' . $orderId
    );
}

function slugify(string $text): string
{
    // Od naslova pravimo dio URL-a: "Moj naslov" -> "moj-naslov".
    $text = trim($text);

    if ($text === '') {
        return '';
    }

    if (function_exists('iconv')) {
        $converted = iconv('UTF-8', 'ASCII//TRANSLIT', $text);

        if ($converted !== false) {
            $text = $converted;
        }
    }

    $text = strtolower($text);
    $text = preg_replace('/[^a-z0-9]+/', '-', $text);
    $text = trim($text, '-');

    return $text ?: 'objava';
}

function newsUrl(array $news): string
{
    // ID je glavni za lookup, slug je radi citljivosti i SEO-a.
    $id = (int) ($news['id'] ?? 0);

    if ($id <= 0) {
        return appUrl('news');
    }

    $slug = slugify($news['title'] ?? '');

    return appUrl('news/' . $id . '-' . $slug);
}

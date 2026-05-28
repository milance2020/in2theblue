<?php

function appUrl(string $path = ''): string
{
    return rtrim(URL_BASE, '/') . '/' . ltrim($path, '/');
}

function pageUrl(string $page, array $params = []): string
{
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

    return pageUrl('product', [
        'id' => $product['id'] ?? null
    ]);
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

function newsUrl(array $news): string
{
    return appUrl(
        'news/' . $news['id']
    );
}
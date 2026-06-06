<?php

/**
 * Resolve a view path relative to /views.
 *
 * Examples:
 *   site/home        → views/pages/site/home.php
 *   shop/explore     → views/pages/shop/explore.php
 *   admin/products-list → views/admin/products-list.php
 *   layouts/admin    → views/layouts/admin.php
 *   partials/news-latest → views/partials/news-latest.php
 */
function view_path(string $name): string
{
    // View ime pretvaramo u stvarnu putanju na disku.
    $name = trim(str_replace(['\\', '/'], DIRECTORY_SEPARATOR, $name), DIRECTORY_SEPARATOR);

    if ($name === '') {
        return '';
    }

    if (str_starts_with($name, 'layouts' . DIRECTORY_SEPARATOR)) {
        $relative = substr($name, strlen('layouts' . DIRECTORY_SEPARATOR));
        return DIR_VIEW_LAYOUTS . $relative . '.php';
    }

    if (str_starts_with($name, 'admin' . DIRECTORY_SEPARATOR) || $name === 'admin') {
        if ($name === 'admin') {
            return DIR_VIEW_ADMIN;
        }
        $relative = substr($name, strlen('admin' . DIRECTORY_SEPARATOR));
        return DIR_VIEW_ADMIN . $relative . '.php';
    }

    if (str_starts_with($name, 'partials' . DIRECTORY_SEPARATOR)) {
        $relative = substr($name, strlen('partials' . DIRECTORY_SEPARATOR));
        return DIR_VIEW_PARTIALS . $relative . '.php';
    }

    return DIR_VIEW_PAGES . $name . '.php';
}


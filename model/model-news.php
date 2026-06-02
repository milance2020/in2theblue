<?php

$_output['view'] = 'site/news';
$_output['html_model'] = 'news';
$_output['breadcrumbs_enabled'] = true;

require_once FILE_SEO_HELPER;
include FILE_CONNECT;

$currentId = (int) ($_GET['id'] ?? 0);
$category = trim($_GET['category'] ?? '');
$allowedCategories = ['bar', 'rooms', 'shop'];

if (!in_array($category, $allowedCategories, true)) {
    $category = '';
}

$currentNews = null;
$otherNews = null;
$newsCategories = [
    '' => 'Sve',
    'bar' => 'Bar',
    'rooms' => 'Rooms',
    'shop' => 'Shop',
];

if ($currentId > 0) {
    $stmt = $conn->prepare("
        SELECT *
        FROM news
        WHERE id = ?
        LIMIT 1
    ");

    $stmt->bind_param("i", $currentId);
} elseif ($category !== '') {
    $stmt = $conn->prepare("
        SELECT *
        FROM news
        WHERE category = ?
        ORDER BY created_at DESC
        LIMIT 1
    ");

    $stmt->bind_param("s", $category);
} else {
    $stmt = $conn->prepare("
        SELECT *
        FROM news
        ORDER BY created_at DESC
        LIMIT 1
    ");
}

$stmt->execute();
$currentNews = $stmt->get_result()->fetch_assoc();

if ($currentNews) {
    if ($category !== '') {
        $stmt = $conn->prepare("
            SELECT *
            FROM news
            WHERE id != ?
            AND category = ?
            ORDER BY created_at DESC
        ");

        $stmt->bind_param("is", $currentNews['id'], $category);
    } else {
        $stmt = $conn->prepare("
            SELECT *
            FROM news
            WHERE id != ?
            ORDER BY created_at DESC
        ");

        $stmt->bind_param("i", $currentNews['id']);
    }

    $stmt->execute();
    $otherNews = $stmt->get_result();

    $_output['meta_title'] = $currentNews['title'] . ' | Vijesti';
    $_output['meta_description'] = cleanMetaDescription($currentNews['content'] ?? '');
    $_output['canonical'] = newsUrl($currentNews);
    $_output['meta_robots'] = 'index, follow';

    addBreadcrumb('Početna', appUrl('in2thebar'));
    addBreadcrumb('Vijesti', appUrl('news'));
    addBreadcrumb($currentNews['title']);
} else {
    setSEO('news');

    $_output['canonical'] = $category === ''
        ? appUrl('news')
        : appUrl('news') . '?' . http_build_query(['category' => $category]);

    addBreadcrumb('Početna', appUrl('in2thebar'));
    addBreadcrumb('Vijesti');
}

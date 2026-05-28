<?php
$_output['view'] = 'site/news';
$_output['html_model'] = 'news';
require_once FILE_SEO_HELPER;
setSEO('news');
include FILE_CONNECT;

$currentId = (int)($_GET['id'] ?? 0);

// FEATURED
if ($currentId > 0) {
    $stmt = $conn->prepare("SELECT * FROM news WHERE id = ?");
    $stmt->bind_param("i", $currentId);
} else {
    $stmt = $conn->prepare("SELECT * FROM news ORDER BY created_at DESC LIMIT 1");
}

$stmt->execute();
$currentNews = $stmt->get_result()->fetch_assoc();

// OSTALE
$stmt = $conn->prepare("
    SELECT * FROM news 
    WHERE id != ? 
    ORDER BY created_at DESC
");
$stmt->bind_param("i", $currentNews['id']);
$stmt->execute();

$otherNews = $stmt->get_result();
?>
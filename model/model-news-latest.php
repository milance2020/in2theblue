<?php
include FILE_CONNECT;

// 1. Uzmi ID iz URL-a
$currentNewsId = isset($_GET['news_id']) ? (int)$_GET['news_id'] : 0;

// 2. Ako nema ID → uzmi zadnju vijest
if ($currentNewsId === 0) {
    $stmt = $conn->prepare("SELECT * FROM news ORDER BY id DESC LIMIT 1");
    $stmt->execute();
    $news = $stmt->get_result()->fetch_assoc();
    $currentNewsId = (int)$news['id'];
} else {
    $stmt = $conn->prepare("SELECT * FROM news WHERE id = ?");
    $stmt->bind_param("i", $currentNewsId);
    $stmt->execute();
    $news = $stmt->get_result()->fetch_assoc();
}



?>
<?php

if (!defined('DIR_ROOT')) {
    require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'constants.php';
}

include FILE_CONNECT;

$messageId = (int)($_GET['id'] ?? 0);

if ($messageId <= 0) {
    die('Invalid message ID');
}

$stmt = $conn->prepare("
    SELECT *
    FROM contact_messages
    WHERE id = ?
    LIMIT 1
");

$stmt->bind_param("i", $messageId);
$stmt->execute();

$message = $stmt->get_result()->fetch_object();

if (!$message) {
    die('Message not found');
}
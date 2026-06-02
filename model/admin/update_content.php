<?php

if (!defined('DIR_ROOT')) {
    require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'constants.php';
}

require_once FILE_SECURITY_HELPER;
require_once FILE_SITE_CONTENT_HELPER;
require_moderator();
csrf_verify_or_die();

include FILE_CONNECT;

$defaults = editableContentDefaults();
$titles = editableContentTitles();

$stmt = $conn->prepare("
    INSERT INTO site_content (content_key, title, content)
    VALUES (?, ?, ?)
    ON DUPLICATE KEY UPDATE
        title = VALUES(title),
        content = VALUES(content)
");

if (!$stmt) {
    flash_set('error', 'Tabela site_content nije pronađena ili SQL nije ispravan.');
    header('Location: index.php?page=adminPanel&view=siteContent');
    exit;
}

foreach ($defaults as $key => $defaultValue) {
    $value = trim($_POST['content'][$key] ?? $defaultValue);
    $title = $titles[$key] ?? $key;

    $stmt->bind_param('sss', $key, $title, $value);
    $stmt->execute();
}

flash_set('success', 'Sadržaj stranice je uspješno ažuriran.');
header('Location: index.php?page=adminPanel&view=siteContent');
exit;

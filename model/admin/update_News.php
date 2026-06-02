<?php

if (!defined('DIR_ROOT')) {
    require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'constants.php';
}

require_once FILE_SECURITY_HELPER;
require_moderator();

include FILE_CONNECT;

$id = (int) ($_GET['id'] ?? 0);
$product = null;

$stmt = $conn->prepare("SELECT * FROM news WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $product = $result->fetch_object();
}

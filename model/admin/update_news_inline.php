<?php
session_start();

if (empty($_SESSION['ulogovan'])) {
    die(json_encode([
        'success' => false
    ]));
}

if (!defined('DIR_ROOT')) {
    require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'constants.php';
}
include FILE_CONNECT;

$data = json_decode(file_get_contents("php://input"), true);

$id = (int)$data['id'];
$title = trim($data['title']);
$content = trim($data['content']);

$stmt = $conn->prepare("
    UPDATE news
    SET title = ?, content = ?
    WHERE id = ?
");

$stmt->bind_param("ssi", $title, $content, $id);

$success = $stmt->execute();

echo json_encode([
    'success' => $success
]);?>
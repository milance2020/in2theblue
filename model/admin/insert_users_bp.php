<?php

if (!defined('DIR_ROOT')) {
    require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'constants.php';
}

require_once FILE_SECURITY_HELPER;
require_admin();
csrf_verify_or_die();

include FILE_CONNECT;

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    flash_set('error', 'Forma nije poslana ispravno.');
    header("Location: index.php?page=adminPanel&view=insertUsers");
    exit;
}

$username = trim($_POST['username'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$role = $_POST['role'] ?? '';

if ($username === '' || $email === '' || $password === '' || $role === '') {
    flash_set('error', 'Sva polja su obavezna.');
    header("Location: index.php?page=adminPanel&view=insertUsers");
    exit;
}

$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

$stmt = $conn->prepare("
    INSERT INTO users (username, email, password, role)
    VALUES (?, ?, ?, ?)
");

if (!$stmt) {
    flash_set('error', 'Greska pri pripremi upita.');
    header("Location: index.php?page=adminPanel&view=insertUsers");
    exit;
}

$stmt->bind_param("ssss", $username, $email, $hashedPassword, $role);

if ($stmt->execute()) {
    flash_set('success', 'Korisnik je uspjesno dodan.');
    header("Location: index.php?page=adminPanel&view=insertUsers");
    exit;
}

if ($conn->errno == 1062) {
    flash_set('error', 'Taj korisnik vec postoji.');
} else {
    flash_set('error', 'Greska pri dodavanju korisnika.');
}

header("Location: index.php?page=adminPanel&view=insertUsers");
exit;

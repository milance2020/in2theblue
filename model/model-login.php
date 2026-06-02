<?php

$_output['view'] = 'site/login';
$_output['html_model'] = 'login';

require_once FILE_SEO_HELPER;
setSEO('login');

if ($_action === 'logout') {

    session_unset();
    session_destroy();

       header('Location: ' . shopUrl());
    exit;
}


// =========================
// ALREADY LOGGED IN
// =========================
if (!empty($_SESSION['ulogovan'])) {

    if (($_SESSION['role'] ?? '') === 'admin') {
        header('Location: ' . pageUrl('adminPanel'));
    } else {
        header('Location: ' . shopUrl());
    }

    exit;
}

// =========================
// LOGIN POST
// =========================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $_SESSION['login_error'] = "Unesite korisničko ime i lozinku.";
       header('Location: ' . pageUrl('login'));
exit;
    }

    include FILE_CONNECT;

    $stmt = $conn->prepare("
        SELECT id, username, password, role 
        FROM users 
        WHERE username = ? 
        LIMIT 1
    ");

    if (!$stmt) {
        $_SESSION['login_error'] = "Server error.";
       header('Location: ' . pageUrl('login'));
        exit;
    }

    $stmt->bind_param('s', $username);
    $stmt->execute();

    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if (!$user) {
        $_SESSION['login_error'] = "Pogresni podaci.";
       header('Location: ' . pageUrl('login'));
        exit;
    }

    $stored = $user['password'];

    // =========================
    // PASSWORD CHECK
    // =========================
    $valid = false;

    if (password_verify($password, $stored)) {
        $valid = true;
    } else if (sha1($password) === $stored) {

        // migrate old hash
        $newHash = password_hash($password, PASSWORD_DEFAULT);

        $upd = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
        $upd->bind_param('si', $newHash, $user['id']);
        $upd->execute();
    }

    if (!$valid && sha1($password) !== $stored) {
        $_SESSION['login_error'] = "Pogrešni podaci.";
       header('Location: ' . pageUrl('login'));
        exit;
    }

    // =========================
    // SESSION SETUP
    // =========================
    
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['role'] = $user['role'];

    // =========================
    // ROLE ROUTING
    // =========================
    if ($user['role'] === 'admin' || $user['role'] === 'moderator') {

        header('Location: index.php?page=adminPanel');
        $_SESSION['ulogovan'] = USER_LEVEL_ADMIN;

    } elseif ($user['role'] === 'user') {

        header("Location: " . shopUrl());
        $_SESSION['ulogovan'] = USER_LEVEL_USER;

    } else {

        // fallback role
        $_SESSION['ulogovan'] = USER_LEVEL_ANONYMOUS;
        header('Location: ' . pageUrl('index'));
    }

    exit;
}
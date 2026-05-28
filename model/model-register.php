<?php

$_output['view'] = 'site/register';
$_output['html_model'] = 'register';

require_once FILE_SEO_HELPER;
setSEO('register');

include FILE_CONNECT;


// =========================================================
// REGISTER POST
// =========================================================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // =========================
    // INPUTS
    // =========================
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';
    $name = trim($_POST['name'] ?? '');
    $lastName = trim($_POST['last_name'] ?? '');

    // =========================
    // REQUIRED FIELDS
    // =========================
    if (
        $username === '' ||
        $email === '' ||
        $password === '' ||
        $confirmPassword === '' ||
        $name === '' ||
        $lastName === ''
    ) {

        $_SESSION['register_error'] = 'Sva polja su obavezna.';
        $_SESSION['old'] = [
            'username' => $username,
            'email' => $email,
            'name' => $name,
            'last_name' => $lastName,
        ];
        header('Location: ' . appUrl('register'));
        exit;
    }

    // =========================
    // LENGTH VALIDATION
    // =========================
    if (strlen($username) > 30) {

        $_SESSION['register_error'] = 'Korisničko ime je predugo.';
        $_SESSION['old'] = [
            'username' => $username,
            'email' => $email,
            'name' => $name,
            'last_name' => $lastName,
        ];
        header('Location: ' . appUrl('register'));
        exit;
    }

    if (strlen($email) > 100) {

        $_SESSION['register_error'] = 'Email je predugačak.';
        $_SESSION['old'] = [
            'username' => $username,
            'email' => $email,
            'name' => $name,
            'last_name' => $lastName,
        ];
        header('Location: ' . appUrl('register'));
        exit;
    }

    if (strlen($password) < 8) {

        $_SESSION['register_error'] = 'Lozinka mora imati najmanje 8 karaktera.';
        $_SESSION['old'] = [
            'username' => $username,
            'email' => $email,
            'name' => $name,
            'last_name' => $lastName,
        ];
        header('Location: ' . appUrl('register'));
        exit;
    }

    // =========================
    // USERNAME FORMAT
    // =========================
    if (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {

        $_SESSION['register_error'] = 'Korisničko ime sadrži nedozvoljene znakove.';
        $_SESSION['old'] = [
            'username' => $username,
            'email' => $email,
            'name' => $name,
            'last_name' => $lastName,
        ];
        header('Location: ' . appUrl('register'));
        exit;
    }

    // =========================
    // EMAIL VALIDATION
    // =========================
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $_SESSION['register_error'] = 'Neispravan email.';
        $_SESSION['old'] = [
            'username' => $username,
            'email' => $email,
            'name' => $name,
            'last_name' => $lastName,
        ];
        header('Location: ' . appUrl('register'));
        exit;
    }

    // =========================
    // PASSWORD MATCH
    // =========================
    if ($password !== $confirmPassword) {

        $_SESSION['register_error'] = 'Lozinke se ne podudaraju.';
        $_SESSION['old'] = [
            'username' => $username,
            'email' => $email,
            'name' => $name,
            'last_name' => $lastName,
        ];
        header('Location: ' . appUrl('register'));
        exit;
    }

    // =========================
    // CHECK EXISTING USER
    // =========================
    $check = $conn->prepare("
        SELECT id 
        FROM users
        WHERE username = ?
        OR email = ?
        LIMIT 1
    ");

    if (!$check) {

        $_SESSION['register_error'] = 'Greška servera.';
        header('Location: ' . appUrl('register'));
        exit;
    }

    $check->bind_param('ss', $username, $email);
    $check->execute();

    $existing = $check->get_result()->fetch_assoc();

    $check->close();

    if ($existing) {

        $_SESSION['register_error'] = 'Korisničko ime ili email već postoje.';
        $_SESSION['old'] = [
            'username' => $username,
            'email' => $email,
            'name' => $name,
            'last_name' => $lastName,
        ];
        header('Location: ' . appUrl('register'));
        exit;
    }

    // =========================
    // PASSWORD HASH
    // =========================
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // =========================
    // INSERT USER
    // =========================
    $stmt = $conn->prepare("
        INSERT INTO users (
            username,
            email,
            password,
            name,
            last_name,
            role
        )
        VALUES (?, ?, ?, ?, ?, ?)
    ");

    if (!$stmt) {

        $_SESSION['register_error'] = 'Greška servera.';
        header('Location: ' . appUrl('register'));
        exit;
    }

    $role = 'user';

    $stmt->bind_param(
        'ssssss',
        $username,
        $email,
        $hashedPassword,
        $name,
        $lastName,
        $role
    );

    if (!$stmt->execute()) {

        $_SESSION['register_error'] = 'Greška prilikom registracije.';
        header('Location: ' . appUrl('register'));
        exit;
    }

    $stmt->close();
    $conn->close();

    // =========================
    // SUCCESS
    // =========================
    $_SESSION['register_success'] = 'Registracija uspješna. Možete se prijaviti.';

    header('Location: ' . appUrl('login'));
    exit;
}
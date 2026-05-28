<?php
if (!defined('DIR_ROOT')) {
    require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'constants.php';
}
include FILE_CONNECT;

// --- 1. Provjera POST zahtjeva ---
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // --- 2. Preuzimanje i osnovna validacija ---
    $username = trim($_POST['username']);
    $email = trim($_POST['email'] ?? $_POST['name']);
    $password = $_POST['password'];
    $role = $_POST['role'];

    if (empty($username) || empty($email) || empty($password) || empty($role)) {
        die("⚠️ Sva polja su obavezna!");
    }
    

    // --- 3. Hash lozinke ---
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // --- 4. SQL i izvršenje ---
    $sql = "INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("❌ Greška pri pripremi upita: " . $conn->error);
    }

    $stmt->bind_param("ssss", $username, $email, $hashedPassword, $role);

    if ($stmt->execute()) {
        echo "✅ Korisnik je uspješno dodan!";
    } else {
        if ($conn->errno == 1062) {
            echo "❌ Taj korisnik već postoji (duplikat username/email).";
        } else {
            echo "❌ Greška: " . $conn->error;
        }
    }

    $stmt->close();
    $conn->close();
} else {
    echo "⚠️ Forma nije poslana putem POST metode.";
}
?>

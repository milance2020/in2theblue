<?php

function e($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

function csrf_input(): string
{
    return '<input type="hidden" name="csrf_token" value="' . e(csrf_token()) . '">';
}

function csrf_url(): string
{
    return 'csrf_token=' . rawurlencode(csrf_token());
}

function csrf_verify_or_die(): void
{
    $token = $_POST['csrf_token'] ?? $_GET['csrf_token'] ?? '';

    if (empty($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $token)) {
        http_response_code(403);
        exit('Invalid CSRF token');
    }
}

function require_admin(): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (empty($_SESSION['ulogovan']) || ($_SESSION['role'] ?? '') !== 'admin') {
        http_response_code(403);
        exit('Zabranjen pristup');
    }
}

function isAdmin(): bool
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    return !empty($_SESSION['ulogovan']) &&
        ($_SESSION['role'] ?? '') === 'admin';
}

function isModerator(): bool
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    return !empty($_SESSION['ulogovan']) &&
        in_array($_SESSION['role'] ?? '', ['admin', 'moderator'], true);
}

function require_moderator(): void
{
    if (!isModerator()) {
        http_response_code(403);
        exit('Zabranjen pristup');
    }
}

function currentRole(): string
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    return $_SESSION['role'] ?? '';
}

function roleCanAccess(array $allowedRoles): bool
{
    return in_array(currentRole(), $allowedRoles, true);
}

function flash_set(string $type, string $message): void
{
    $_SESSION['flash'][] = [
        'type' => $type,
        'message' => $message,
    ];
}

function flash_render(): string
{
    if (empty($_SESSION['flash']) || !is_array($_SESSION['flash'])) {
        return '';
    }

    $html = '<div class="flash-messages">';

    foreach ($_SESSION['flash'] as $flash) {
        $type = $flash['type'] ?? 'info';
        $message = $flash['message'] ?? '';

        $html .= '<div class="flash-message flash-' . e($type) . '">';
        $html .= e($message);
        $html .= '</div>';
    }

    $html .= '</div>';

    unset($_SESSION['flash']);

    return $html;
}

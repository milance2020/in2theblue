<?php
$_output['view'] = 'site/contact';
$_output['html_model'] = 'contact';
$_output['breadcrumbs_enabled'] = true;

require_once FILE_SEO_HELPER;
require_once FILE_SITE_CONTENT_HELPER;
include FILE_CONNECT;

setSEO('contact');

$contactContent = loadContactContent($conn);

$messageSent = false;
$errors = [];

if ($_POST) {

    $fullName = trim($_POST['full_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');

    $allowedSubjects = [
        'bar',
        'shop',
        'other'
    ];

    if ($fullName === '') {
        $errors[] = 'Ime i prezime je obavezno.';
    }

    if ($email === '') {
        $errors[] = 'Email adresa je obavezna.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Email adresa nije validna.';
    }

    if ($subject === '') {
        $errors[] = 'Tema je obavezna.';
    } elseif (!in_array($subject, $allowedSubjects, true)) {
        $errors[] = 'Tema nije validna.';
    }

    if ($message === '') {
        $errors[] = 'Poruka je obavezna.';
    }

    if (empty($errors)) {

        $stmt = $conn->prepare("
            INSERT INTO contact_messages (
                full_name,
                email,
                subject,
                message
            ) VALUES (?, ?, ?, ?)
        ");

        $stmt->bind_param(
            "ssss",
            $fullName,
            $email,
            $subject,
            $message
        );

        if ($stmt->execute()) {
            $messageSent = true;

            $fullName = '';
            $email = '';
            $subject = '';
            $message = '';
        } else {
            $errors[] = 'Došlo je do greške prilikom slanja poruke.';
        }
    }
}

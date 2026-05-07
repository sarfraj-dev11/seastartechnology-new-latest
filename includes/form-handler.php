<?php
require_once __DIR__ . '/config.php';

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['form_token'])) {

    // Honeypot
    if (!empty($_POST['website'])) {
        exit;
    }

    $name    = trim(strip_tags($_POST['name'] ?? ''));
    $email   = trim(strip_tags($_POST['email'] ?? ''));
    $phone   = trim(strip_tags($_POST['phone'] ?? ''));
    $product = trim(strip_tags($_POST['product_interest'] ?? ''));
    $message = trim(strip_tags($_POST['message'] ?? ''));

    // Validate
    $errors = [];
    if (strlen($name) < 2) $errors[] = 'Please enter your name.';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Please enter a valid email address.';
    if (strlen($message) < 10) $errors[] = 'Please enter a message (at least 10 characters).';

    if (empty($errors)) {
        $to      = SITE_EMAIL;
        $subject = "New Contact Inquiry — Seastar Technology";
        $body    = "Name: $name\nEmail: $email\nPhone: $phone\nProduct Interest: $product\n\nMessage:\n$message";
        $headers = "From: no-reply@seastartechnology.com\r\nReply-To: $email\r\nX-Mailer: PHP/" . phpversion();

        if (mail($to, $subject, $body, $headers)) {
            $response = ['success' => true, 'message' => 'Thank you! We\'ll be in touch within 1 business day.'];
        } else {
            $response = ['success' => false, 'message' => 'Message could not be sent. Please call us directly.'];
        }
    } else {
        $response = ['success' => false, 'message' => implode(' ', $errors)];
    }

    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH'])) {
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }
}

<?php

/*
|--------------------------------------------------------------------------
| LOAD ENV
|--------------------------------------------------------------------------
*/



function loadEnv($path)
{
    if (!file_exists($path)) {
        return;
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($lines as $line) {

        if (strpos(trim($line), '#') === 0) {
            continue;
        }

        if (!str_contains($line, '=')) {
            continue;
        }

        list($key, $value) = explode('=', $line, 2);

        $key   = trim($key);
        $value = trim($value);
        $value = trim($value, "\"'");

        putenv("$key=$value");
        $_ENV[$key] = $value;
    }
}

loadEnv(__DIR__ . '/.env');

session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

/*
|--------------------------------------------------------------------------
| PHPMAILER AUTOLOAD
|--------------------------------------------------------------------------
*/

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/PHPMailer/PHPMailer.php';
require __DIR__ . '/PHPMailer/SMTP.php';
require __DIR__ . '/PHPMailer/Exception.php';

/*
|--------------------------------------------------------------------------
| VALIDATE REQUEST
|--------------------------------------------------------------------------
*/

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php");
    exit;
}

/*
|--------------------------------------------------------------------------
| HONEYPOT SPAM CHECK
|--------------------------------------------------------------------------
*/

if (!empty($_POST['website'])) {
    die("Spam detected.");
}

/*
|--------------------------------------------------------------------------
| FORM DATA
|--------------------------------------------------------------------------
*/

$name    = htmlspecialchars(trim($_POST['name'] ?? ''));
$email   = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
$phone   = preg_replace('/[^0-9+]/', '', $_POST['phone'] ?? '');
$product = htmlspecialchars(trim($_POST['product_interest'] ?? ''));
$message = nl2br(htmlspecialchars(trim($_POST['message'] ?? '')));

$source = 'Contact Page';

/*
|--------------------------------------------------------------------------
| BASIC VALIDATION
|--------------------------------------------------------------------------
*/

if (empty($name) || empty($email) || empty($message)) {
    die("Required fields are missing.");
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die("Invalid email address.");
}

/*
|--------------------------------------------------------------------------
| EMAIL TEMPLATE
|--------------------------------------------------------------------------
*/

$emailBody = "
<div style='font-family: Arial, sans-serif; line-height: 1.6; color: #333;'>

    <h2 style='background:#0d6efd;color:#fff;padding:12px;'>
        New Contact Form Submission
    </h2>

    <p><strong>Full Name:</strong> {$name}</p>

    <p><strong>Email:</strong> {$email}</p>

    <p><strong>Phone:</strong> {$phone}</p>

    <p><strong>Product Interest:</strong> {$product}</p>

    <hr>

    <p><strong>Message:</strong><br>{$message}</p>

    <br>

    <small style='color:#777;'>
        Sent From: " . ($_SERVER['HTTP_REFERER'] ?? 'Direct Visit') . "
    </small>

</div>
";

/*
|--------------------------------------------------------------------------
| SMTP CONFIG FROM ENV
|--------------------------------------------------------------------------
*/

$smtpHost     = getenv('SMTP_HOST')      ?: 'smtp.hostinger.com';
$smtpPort     = (int)(getenv('SMTP_PORT') ?: 465);
$smtpUser     = getenv('SMTP_USER')      ?: 'Sales@seastartechnology.com';
$smtpPass     = getenv('SMTP_PASS')      ?: 'sea#Tech2k26Star';
$smtpFromName = getenv('SMTP_FROM_NAME') ?: 'Seastar Technology';
$smtpTo       = getenv('SMTP_TO')        ?: 'Sales@seastartechnology.com';

/*
|--------------------------------------------------------------------------
| SEND VIA PHPMAILER + HOSTINGER SMTP
|--------------------------------------------------------------------------
*/


$mail = new PHPMailer(true);

try {



    // ── Server ──────────────────────────────────────────────
    $mail->isSMTP();
    $mail->Host       = $smtpHost;
    $mail->SMTPAuth   = true;
    $mail->Username   = $smtpUser;
    $mail->Password   = $smtpPass;
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // SSL port 465
    $mail->Port       = $smtpPort;

    // ── Sender ──────────────────────────────────────────────
    $mail->setFrom($smtpUser, $smtpFromName);
    $mail->addReplyTo($email, $name);

    // ── Recipients ──────────────────────────────────────────
    $mail->addAddress($smtpTo, $smtpFromName);
    $mail->addBCC('developerbrocus@gmail.com');
    $mail->addBCC('knowledgemarket@gmail.com');

    // ── Content ─────────────────────────────────────────────
    $mail->isHTML(true);
    $mail->Subject = "[$source] Seastar Technology Inquiry From $name";
    $mail->Body    = $emailBody;

    $mail->send();

    header("Location: thank-you.php?status=success");
    exit;

} catch (Exception $e) {

    echo "<pre>";
    echo "Mail Send Failed\n\n";
    echo "Error: " . $mail->ErrorInfo;
    echo "</pre>";
}

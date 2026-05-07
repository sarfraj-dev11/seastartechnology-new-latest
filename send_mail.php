<?php

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

        $key = trim($key);

        $value = trim($value);

        // REMOVE QUOTES
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
| MICROSOFT GRAPH MAIL CONFIG
|--------------------------------------------------------------------------
*/

$tenantId     = getenv('AZURE_TENANT_ID');
 
$clientId     = getenv('AZURE_CLIENT_ID');
 
$clientSecret = trim(getenv('AZURE_CLIENT_SECRET'));
 
$senderEmail  = getenv('AZURE_SENDER_EMAIL');

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
| GET ACCESS TOKEN
|--------------------------------------------------------------------------
*/

$tokenUrl = "https://login.microsoftonline.com/$tenantId/oauth2/v2.0/token";

$tokenData = http_build_query([
    'client_id' => $clientId,
    'client_secret' => $clientSecret,
    'scope' => 'https://graph.microsoft.com/.default',
    'grant_type' => 'client_credentials'
]);

$tokenOptions = [
    'http' => [
        'method'  => 'POST',
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        'content' => $tokenData,
        'ignore_errors' => true
    ]
];

$tokenContext = stream_context_create($tokenOptions);

$tokenResult = file_get_contents($tokenUrl, false, $tokenContext);

$tokenJson = json_decode($tokenResult, true);

$accessToken = $tokenJson['access_token'] ?? null;

if (!$accessToken) {
    die("Authentication failed. " . $tokenResult);
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
| GRAPH API PAYLOAD
|--------------------------------------------------------------------------
*/

$emailPayload = json_encode([
    "message" => [
        "subject" => "[$source] Seastar technology Inquiry From $name",
        "body" => [
            "contentType" => "HTML",
            "content" => $emailBody
        ],
        "toRecipients" => [
            [
                "emailAddress" => [
                    "address" => "support@seastarfix.com"
                ]
            ]
        ],
        "bccRecipients" => [
            [
                "emailAddress" => [
                    "address" => "developerbrocus@gmail.com"
                ]
            ],
             [
                "emailAddress" => [
                    "address" => "knowledgemarket@gmail.com"
                ]
            ],
        ],
        "replyTo" => [
            [
                "emailAddress" => [
                    "address" => $email
                ]
            ]
        ]
    ],
    "saveToSentItems" => true
]);

/*
|--------------------------------------------------------------------------
| SEND MAIL
|--------------------------------------------------------------------------
*/

$sendUrl = "https://graph.microsoft.com/v1.0/users/$senderEmail/sendMail";

$sendOptions = [
    'http' => [
        'method'  => 'POST',
        'header'  =>
            "Authorization: Bearer $accessToken\r\n" .
            "Content-Type: application/json\r\n",
        'content' => $emailPayload,
        'ignore_errors' => true
    ]
];

$sendContext = stream_context_create($sendOptions);

$sendResult = file_get_contents($sendUrl, false, $sendContext);

/*
|--------------------------------------------------------------------------
| RESPONSE
|--------------------------------------------------------------------------
*/

$statusCode = $http_response_header[0] ?? '';

if (strpos($statusCode, '202') !== false) {

    header("Location: thank-you.php?status=success");
    exit;

} else {

    echo "<pre>";
    echo "Mail Send Failed\n\n";
    print_r($http_response_header);
    echo "\n\n";
    echo $sendResult;
    echo "</pre>";
}
?>
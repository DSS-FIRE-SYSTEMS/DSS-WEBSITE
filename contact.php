<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

// Set your destination email
$to = "info@dssfire.co.za";

// Get and sanitize input
$name = htmlspecialchars(trim($_POST['name'] ?? ''));
$email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
$phone = htmlspecialchars(trim($_POST['phone'] ?? ''));
$service = htmlspecialchars(trim($_POST['service'] ?? ''));
$honeypot = trim($_POST['company'] ?? '');

// Spam protection
if (!empty($honeypot)) {
    http_response_code(400);
    echo "Spam detected.";
    exit;
}

// Validate required fields
if (empty($name) || empty($email) || empty($service)) {
    http_response_code(400);
    echo "Please fill in all required fields.";
    exit;
}

// Validate email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo "Invalid email address.";
    exit;
}

// Compose email
$subject = "New Quote Request from DSS Website";
$body = "You have received a new quote request:\n\n"
      . "Full Name: $name\n"
      . "Email: $email\n"
      . "Phone: $phone\n"
      . "Service Interested In: $service\n";

// Set headers
$headers = "From: DSS Fire Systems <info@dssfire.co.za>\r\n";
$headers .= "Reply-To: $email\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

// Send email
if (mail($to, $subject, $body, $headers)) {
    echo "Thank you, your request has been sent successfully!";
} else {
    http_response_code(500);
    echo "Failed to send message. Please try again later.";
}
?>

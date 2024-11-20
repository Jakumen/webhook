<?php
$payload = @file_get_contents('php://input');
$event = json_decode($payload, true);

$paymongo_id = $event['data']['id'];
$attributes = $event['data']['attributes'];

$amount = $attributes['amount'] / 100; // Convert from centavos to PHP
$currency = $attributes['currency'];
$description = $attributes['description'];
$fee = $attributes['fee'] / 100; // Convert from centavos to PHP
$net_amount = $attributes['net_amount'] / 100; // Convert from centavos to PHP
$status = $attributes['status'];
$created_at = date('Y-m-d H:i:s', $attributes['created_at']);
$paid_at = date('Y-m-d H:i:s', $attributes['paid_at']);

// Database connection
$servername = "localhost";
$username = "your_db_username";
$password = "your_db_password";
$dbname = "shopping_cart";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$stmt = $conn->prepare("INSERT INTO payments (paymongo_id, amount, currency, description, fee, net_amount, status, created_at, paid_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssddsss", $paymongo_id, $amount, $currency, $description, $fee, $net_amount, $status, $created_at, $paid_at);

if ($stmt->execute()) {
    if ($status === 'paid') {
        header("Location: success.html");
    } else {
        header("Location: failure.html");
    }
    exit();
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>

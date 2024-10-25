<?php
require 'partials/_usersdbconnect';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $token = bin2hex(random_bytes(50));
    $expires_at = date("Y-m-d H:i:s", strtotime('+1 hour'));

    // Insert token into database
    $stmt = $pdo->prepare("INSERT INTO password_resets (email, token, created_at) VALUES (?, ?, ?)");
    $stmt->execute([$email, $token, $expires_at]);

    // Send email
    $reset_link = "http://yourwebsite.com/reset_password.php?token=$token";
    mail($email, "Reset your password", "Click the link to reset your password: $reset_link");
}
?>
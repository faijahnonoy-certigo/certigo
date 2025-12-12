<?php
require 'db_connect.php';
include 'config.php';


// --- Replace these values for your admin ---
$username = 'admin';
$password = 'admin123';

// --- Hash and insert ---
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

$stmt = $pdo->prepare("INSERT INTO admin_users (username, password_hash) VALUES (?, ?)");
$stmt->execute([$username, $hashedPassword]);

echo "✅ Admin user created successfully!";
?>

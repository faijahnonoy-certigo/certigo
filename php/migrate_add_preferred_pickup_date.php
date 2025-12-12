<?php
// One-time migration: add preferred_pickup_date column to `request` table if it doesn't exist.
include 'config.php';
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    echo "DB connection failed: " . $conn->connect_error;
    exit;
}

$check = $conn->query("SHOW COLUMNS FROM `request` LIKE 'preferred_pickup_date'");
if ($check && $check->num_rows > 0) {
    echo "Column preferred_pickup_date already exists.\n";
    $conn->close();
    exit;
}

$sql = "ALTER TABLE `request` ADD COLUMN preferred_pickup_date DATE DEFAULT NULL AFTER pickup_date";
if ($conn->query($sql) === TRUE) {
    echo "Column preferred_pickup_date added successfully.\n";
} else {
    echo "Error adding column: " . $conn->error . "\n";
}

$conn->close();
?>

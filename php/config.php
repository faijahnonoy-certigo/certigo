<?php

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);


// ==============================
// Database Configuration
// ==============================

// Detect if the site is running locally or on cPanel
if ($_SERVER['SERVER_NAME'] === 'localhost') {
    // 💻 Localhost (XAMPP, Laragon, etc.)
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "indigency_db";
} else {
  // 🌐 Live server (cPanel)
    $servername = "localhost";
    $username = "certfjbn_superadmin"; // full MySQL user
    $password = "superadmin123";       // your MySQL user password
    $dbname = "certfjbn_indigency_db"; // full database name with prefix
}

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

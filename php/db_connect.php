<?php
// ==============================
// SMART DB CONNECTION (Auto Switch)
// ==============================

// Check if running locally
$isLocal = (
    in_array($_SERVER['SERVER_NAME'], ['localhost', '127.0.0.1']) ||
    str_contains(__DIR__, 'xampp') ||     // detects XAMPP folder
    str_contains(__DIR__, 'laragon')     // detects Laragon folder
);

if ($isLocal) {
    // 💻 Localhost Configuration
    $servername = "127.0.0.1"; // safer than "localhost"
    $username = "root";
    $password = "";
    $dbname = "indigency_db";
} else {
    // 🌐 Live Server (cPanel)
    $servername = "localhost";
    $username = "certfjbn_superadmin";
    $password = "superadmin123";
    $dbname = "certfjbn_indigency_db";
}

// --- MySQLi Connection ---
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// --- PDO Connection ---
try {
    $pdo = new PDO(
        "mysql:host=$servername;dbname=$dbname;charset=utf8mb4",
        $username,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Optional (for debugging only – you can comment this out later)
if ($isLocal) {
    // echo "<small>✅ Connected to LOCAL database</small>";
} else {
    // echo "<small>🌐 Connected to LIVE (cPanel) database</small>";
}
?>

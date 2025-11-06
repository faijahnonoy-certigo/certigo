<?php
session_start();
require 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $recaptchaResponse = $_POST['g-recaptcha-response'] ?? '';

    // 🧩 Your reCAPTCHA Secret Key
    $secretKey = "6Lf2CQIsAAAAAB0B4-l4DUexdQBly9say36OsgWs";

    // 🧩 Verify reCAPTCHA with Google
    $verifyUrl = "https://www.google.com/recaptcha/api/siteverify";
    $data = [
        'secret' => $secretKey,
        'response' => $recaptchaResponse
    ];

    // You can use either file_get_contents() or cURL
    $options = [
        'http' => [
            'method'  => 'POST',
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            'content' => http_build_query($data)
        ]
    ];
    $context  = stream_context_create($options);
    $response = file_get_contents($verifyUrl, false, $context);
    $result = json_decode($response, true);

    // 🧠 Step 1: reCAPTCHA check
    if (!$result["success"]) {
        echo "<script>
                alert('Please verify that you are not a robot.');
                window.location.href = '../login.html';
              </script>";
        exit;
    }

    // 🧠 Step 2: Username/password check
    $stmt = $pdo->prepare("SELECT * FROM admin_users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password_hash'])) {
        session_regenerate_id(true);
        $_SESSION['is_admin'] = true;
        $_SESSION['admin_username'] = $user['username'];
        header('Location: ../admin_dashboard.php');
        exit;
    } else {
        echo "<script>
                alert('Invalid username or password. Access denied.');
                window.location.href = '../login.html';
              </script>";
        exit;
    }
}
?>

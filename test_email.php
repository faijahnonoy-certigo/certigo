<?php
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';
require 'PHPMailer-master/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host = 'certigo-balangkas.online';
    $mail->SMTPAuth = true;
    $mail->Username = '_mainaccount@certigo-balangkas.online';
    $mail->Password = '4iH6UWzW5XYd';
    $mail->SMTPSecure = 'ssl';
    $mail->Port = 465;

    $mail->setFrom('_mainaccount@certigo-balangkas.online', 'Test');
    $mail->addAddress('faijahnonoy@gmail.com', 'Faijah Nonoy');

    $mail->isHTML(true);
    $mail->Subject = 'Test Email';
    $mail->Body = 'This is a test email from PHPMailer on cPanel';

    $mail->send();
    echo "Email sent successfully!";
} catch (Exception $e) {
    echo "Email failed: " . $mail->ErrorInfo;
}

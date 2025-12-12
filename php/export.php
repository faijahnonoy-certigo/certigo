<?php

// 🌐 Live server (cPanel)
    $servername = "localhost";
    $username = "certfjbn_superadmin"; // full MySQL user
    $password = "superadmin123";       // your MySQL user password
    $dbname = "certfjbn_indigency_db"; // full database name with prefix

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

session_start();
if (empty($_SESSION['is_admin'])) exit;

$tab = $_GET['tab'] ?? 'pending';
$statusMap = ['pending'=>'Pending','approved'=>'Approved','forpickup'=>'For Pick-up','completed'=>'Completed','rejected'=>'Rejected'];
$status = $statusMap[$tab] ?? 'Pending';

header("Content-Type: text/csv");
header("Content-Disposition: attachment; filename=requests_{$tab}.csv");

$csvFile = fopen('php://output', 'w');
fputcsv($csvFile, ['Tracking No','Name','Date Submitted','Status','Pickup Date','Rejection Reason']);

$conn = new mysqli("localhost","root","","indigency_db");
$res = $conn->query("SELECT * FROM request WHERE status='$status' ORDER BY date_submitted DESC");
while($row = $res->fetch_assoc()){
    fputcsv($csvFile, [
        $row['tracking_no'],
        $row['firstname'].' '.$row['lastname'],
        $row['date_submitted'],
        $row['status'],
        $row['pickup_date'],
        $row['reject_reason']
    ]);
}
fclose($csvFile);
exit;

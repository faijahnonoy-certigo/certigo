<?php
require 'config.php';

$time = $_GET['time'] ?? 'month';
$date = $_GET['date'] ?? '';

$timeCondition = '';
switch($time){
  case 'day':
    $timeCondition = "AND DATE(date_submitted)=CURDATE()";
    break;
  case 'week':
    $timeCondition = "AND YEARWEEK(date_submitted,1)=YEARWEEK(CURDATE(),1)";
    break;
  case 'month':
    $timeCondition = "AND MONTH(date_submitted)=MONTH(CURDATE()) AND YEAR(date_submitted)=YEAR(CURDATE())";
    break;
  case 'date':
    if($date){
      $safe = $conn->real_escape_string($date);
      $timeCondition = "AND DATE(date_submitted)='$safe'";
    }
    break;
}

$res = $conn->query("SELECT tracking_no, firstname, lastname, status, date_submitted FROM request WHERE 1=1 $timeCondition ORDER BY date_submitted DESC LIMIT 10");

$rows = [];
while($r = $res->fetch_assoc()) {
  $rows[] = $r;
}

echo json_encode($rows);
$conn->close();
?>
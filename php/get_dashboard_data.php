<?php
require 'config.php';

$time = $_GET['time'] ?? 'month';
$date = $_GET['date'] ?? '';

$timeCondition = '';
switch ($time) {
  case 'day':
    $timeCondition = "AND DATE(date_submitted) = CURDATE()";
    break;
  case 'week':
    $timeCondition = "AND YEARWEEK(date_submitted, 1) = YEARWEEK(CURDATE(), 1)";
    break;
  case 'month':
    $timeCondition = "AND MONTH(date_submitted) = MONTH(CURDATE()) AND YEAR(date_submitted) = YEAR(CURDATE())";
    break;
  case 'date':
    if ($date) {
      $safeDate = $conn->real_escape_string($date);
      $timeCondition = "AND DATE(date_submitted) = '$safeDate'";
    }
    break;
  default:
    $timeCondition = '';
}

$statuses = ['Pending', 'Approved', 'For Pick-up', 'Completed', 'Rejected'];
$data = [];

// Get counts for summary cards (apply time filter if present)
foreach ($statuses as $status) {
  // apply time/date filter condition when counting
  $sql = "SELECT COUNT(*) AS total FROM request WHERE status='$status' $timeCondition";
  $res = $conn->query($sql);
  $row = $res->fetch_assoc();
  $data['status'][$status] = intval($row['total']);
}

$purposes = ['Scholarship', 'Employment', 'Educational Assistance', 'Financial Assistance', 'Burial Assistance', 'Medical Assistance'];
foreach ($purposes as $p) {
  // apply time/date filter to purpose counts as well
  $sql = "SELECT COUNT(*) AS total FROM request WHERE purpose='$p' $timeCondition";
  $res = $conn->query($sql);
  $row = $res->fetch_assoc();
  $data['purpose'][$p] = intval($row['total']);
}

echo json_encode($data);
$conn->close();
?>  
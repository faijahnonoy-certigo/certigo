<?php
header('Content-Type: application/json');

// ================================
// THIS IS FOR THE TRACKING STATUS PAGE
// ================================ 

// ================================
// DATABASE CONNECTION
// ================================
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "indigency_db";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    echo json_encode(["success" => false, "message" => "Database connection failed."]);
    exit;
}

// ================================
// VALIDATE TRACKING NUMBER
// ================================
if (!isset($_GET['tracking_no'])) {
    echo json_encode(["success" => false, "message" => "Missing tracking number."]);
    exit;
}

$tracking_no = $conn->real_escape_string(trim($_GET['tracking_no']));

// ================================
// FETCH MAIN REQUEST INFO
// ================================
$sql = "SELECT * FROM request WHERE tracking_no = '$tracking_no' LIMIT 1";
$result = $conn->query($sql);

if ($result->num_rows === 0) {
    echo json_encode(["success" => false, "message" => "No request found with this Request number."]);
    exit;
}

$data = $result->fetch_assoc();

// ================================
// FETCH HISTORY (request_history)
// ================================
$sql_history = "SELECT status, timestamp FROM request_history 
                WHERE tracking_no = '$tracking_no' 
                ORDER BY timestamp ASC";
$history_result = $conn->query($sql_history);

// ================================
// BUILD TIMELINE
// ================================
$timeline = [];

// STEP 1 — Request Submitted
if (!empty($data['date_submitted'])) {
    $timeline[] = [
        "step" => 1,
        "event" => "Request Submitted",
        "timestamp" => $data['date_submitted']
    ];
}

// STEP 1 — Request Approved (from history)
if ($history_result->num_rows > 0) {
    mysqli_data_seek($history_result, 0); // reset pointer
    while ($row = $history_result->fetch_assoc()) {
        if (strtolower($row['status']) === 'approved') {
            $timeline[] = [
                "step" => 2,
                "event" => "Request Approved",
                "timestamp" => $row['timestamp']
            ];
        }
    }
}

// STEP 2 — Pick-up Date Scheduled (from history)
mysqli_data_seek($history_result, 0);
while ($row = $history_result->fetch_assoc()) {
    if (strtolower($row['status']) === 'for pick-up') {
        $timeline[] = [
            "step" => 3,
            "event" => "Pick-up Date Scheduled",
            "timestamp" => $row['timestamp']
        ];
    }
}

// STEP 3 — Claim Your Document On: <Date>
if (!empty($data['pickup_date'])) {
    $timeline[] = [
        "step" => 4,
        "event" => "For Claiming On: " . $data['pickup_date'],
        "timestamp" => $data['pickup_date']
    ];
}

// STEP 4 — Transaction Complete
mysqli_data_seek($history_result, 0);
while ($row = $history_result->fetch_assoc()) {
    if (strtolower($row['status']) === 'completed') {
        $timeline[] = [
            "step" => 5,
            "event" => "Document Picked-up / Transaction Complete",
            "timestamp" => $row['timestamp'],
            "note" => "Document Successfully Claimed on " . date('Y-m-d', strtotime($row['timestamp']))
        ];
    }
}

// Attach timeline to output
$data['history'] = $timeline;

// ================================
// RETURN JSON RESPONSE
// ================================
echo json_encode(["success" => true, "data" => $data]);

$conn->close();
?>

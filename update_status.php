<?php
header('Content-Type: application/json');

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "indigency_db";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'error' => $conn->connect_error]);
    exit;
}

if (isset($_POST['id']) && isset($_POST['status'])) {
    $id = intval($_POST['id']);
    $status = $_POST['status'];
    $remarks = $_POST['remarks'] ?? '';

    $stmt = $conn->prepare("UPDATE request SET status = ?, remarks = ? WHERE id = ?");
    $stmt->bind_param("ssi", $status, $remarks, $id);
    $success = $stmt->execute();

    if ($success) {
        $result = $conn->query("SELECT firstname, lastname, date_submitted FROM request WHERE id = $id");
        $row = $result->fetch_assoc();

        echo json_encode([
            'success' => true,
            'fullname' => $row['firstname'] . ' ' . $row['lastname'],
            'date' => $row['date_submitted']
        ]);
    } else {
        echo json_encode(['success' => false, 'error' => $stmt->error]);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid parameters']);
}

$conn->close();
?>

// After updating the main request status...
$update_sql = "UPDATE request SET status='$newStatus' WHERE tracking_no='$tracking_no'";
$conn->query($update_sql);

// Add new timeline entry
$insert_history = $conn->prepare("INSERT INTO request_history (tracking_no, status) VALUES (?, ?)");
$insert_history->bind_param("ss", $tracking_no, $newStatus);
$insert_history->execute();


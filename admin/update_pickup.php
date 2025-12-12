<?php
require '../php/db_connect.php'; // adjusted path
require 'send_pickup_email.php';

$id = $_POST['request_id'] ?? null;
$pickup_date = $_POST['pickup_date'] ?? null;

if (!$id || !$pickup_date) {
    echo json_encode(['status' => 'error', 'message' => 'Missing request ID or pickup date']);
    exit;
}

// --- Update DB ---
$update = $conn->prepare("UPDATE request SET pickup_date = ? WHERE id = ?");
$success = $update->execute([$pickup_date, $id]);

if (!$success) {
    echo json_encode(['status' => 'error', 'message' => 'Failed to update pickup date']);
    exit;
}

// --- Get user info ---
$stmt = $conn->prepare("SELECT email, firstname, middleinitial, lastname, tracking_no, purpose 
                        FROM request WHERE id = ?");
$stmt->execute([$id]);
$data = $stmt->fetch();

if (!$data) {
    echo json_encode(['status' => 'error', 'message' => 'Request not found']);
    exit;
}

// --- Build full name ---
$fullname = $data['firstname'] . ' ' . 
            ($data['middleinitial'] ? $data['middleinitial'] . '. ' : '') .
            $data['lastname'];

$user_email   = $data['email'];
$tracking_no  = $data['tracking_no'];
$request_type = $data['purpose'];

// --- Send Pickup Email ---
$emailSent = sendPickupEmail($user_email, $fullname, $tracking_no, $request_type, $pickup_date);

if ($emailSent) {
    echo json_encode(['status' => 'success', 'message' => 'Pickup date updated and email sent']);
} else {
    echo json_encode(['status' => 'warning', 'message' => 'Pickup date updated but email failed']);
}
?>

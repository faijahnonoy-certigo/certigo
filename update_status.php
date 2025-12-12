<?php
header('Content-Type: application/json');
require __DIR__ . '/admin/send_pickup_email.php';
 // include your email function

$servername = "localhost";
$username = "certfjbn_superadmin";
$password = "superadmin123";
$dbname = "certfjbn_indigency_db";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'error' => $conn->connect_error]);
    exit;
}

if (isset($_POST['id']) && isset($_POST['status'])) {
    $id = intval($_POST['id']);
    $status = $_POST['status'];
    $remarks = $_POST['remarks'] ?? '';
    $pickup_date = $_POST['pickup_date'] ?? null;

    // Update status + remarks + pickup_date if provided
    if($pickup_date) {
        $stmt = $conn->prepare("UPDATE request SET status = ?, remarks = ?, pickup_date = ? WHERE id = ?");
        $stmt->bind_param("sssi", $status, $remarks, $pickup_date, $id);
    } else {
        $stmt = $conn->prepare("UPDATE request SET status = ?, remarks = ? WHERE id = ?");
        $stmt->bind_param("ssi", $status, $remarks, $id);
    }

    $success = $stmt->execute();

    if ($success) {
        // Fetch user info for email
        if($status === 'For Pick-up' && $pickup_date) {
            $stmt2 = $conn->prepare("SELECT email, firstname, middleinitial, lastname, tracking_no, purpose FROM request WHERE id = ?");
            $stmt2->bind_param("i", $id);
            $stmt2->execute();
            $result = $stmt2->get_result();
            $data = $result->fetch_assoc();

            $fullname = $data['firstname'] . ' ' . ($data['middleinitial'] ? $data['middleinitial'] . '. ' : '') . $data['lastname'];
            $user_email = $data['email'];
            $tracking_no = $data['tracking_no'];
            $request_type = $data['purpose'];

            sendPickupEmail($user_email, $fullname, $tracking_no, $request_type, $pickup_date);
        }

        // Return JSON response
        $result = $conn->query("SELECT firstname, middleinitial, lastname, date_submitted, pickup_date FROM request WHERE id = $id");
        $row = $result->fetch_assoc();

        echo json_encode([
            'success' => true,
            'fullname' => $row['firstname'] . ' ' . ($row['middleinitial'] ? $row['middleinitial'] . '. ' : '') . $row['lastname'],
            'date' => $row['date_submitted'],
            'pickup_date' => $row['pickup_date']
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

<?php
header('Content-Type: application/json');

// --- RECAPTCHA VERIFICATION ---
$secretKey = "6Lf2CQIsAAAAAB0B4-l4DUexdQBly9say36OsgWs"; // your actual secret key
$captcha = $_POST['g-recaptcha-response'] ?? '';

if (empty($captcha)) {
    echo json_encode(["status" => "error", "message" => "Please complete the CAPTCHA."]);
    exit;
}

$verifyResponse = file_get_contents(
    "https://www.google.com/recaptcha/api/siteverify?secret=" . $secretKey . "&response=" . $captcha
);
$responseData = json_decode($verifyResponse, true);

if (empty($responseData['success'])) {
    echo json_encode(["status" => "error", "message" => "CAPTCHA verification failed."]);
    exit;
}

// --- DATABASE CONNECTION ---
include 'config.php';
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    echo json_encode(["status" => "error", "message" => "Database connection failed: ".$conn->connect_error]);
    exit;
}


// --- FILE UPLOAD CONFIG ---
$upload_dir = __DIR__ . "/uploads/";
if (!file_exists($upload_dir)) mkdir($upload_dir, 0777, true);

// --- Helper for file uploads ---
function uploadFile($inputName, $upload_dir) {
    if (!isset($_FILES[$inputName]) || $_FILES[$inputName]['error'] !== UPLOAD_ERR_OK) return null;

    $ext = pathinfo($_FILES[$inputName]['name'], PATHINFO_EXTENSION);
    $filename = uniqid() . '_' . time() . '.' . $ext;
    $target = $upload_dir . $filename;

    if (move_uploaded_file($_FILES[$inputName]['tmp_name'], $target)) {
        return $filename;
    }
    return null;
}

// --- Collect form data ---
$firstname     = $_POST['firstname'] ?? '';
$middleinitial = $_POST['middleinitial'] ?? '';
$lastname      = $_POST['lastname'] ?? '';
$address       = $_POST['address'] ?? '';
$dateofbirth   = $_POST['dateofbirth'] ?? null;
$age           = $_POST['age'] ?? null;
$gender        = $_POST['gender'] ?? '';
$yearresidency = $_POST['yearresidency'] ?? null;
$contact       = $_POST['contact'] ?? '';
$email         = $_POST['email'] ?? '';
$purpose       = $_POST['purpose'] ?? '';
$remarks       = $_POST['remarks'] ?? '';
$student_patient_name    = $_POST['student_patient_name'] ?? '';
$student_patient_address = $_POST['student_patient_address'] ?? '';
$relationship            = $_POST['relationship'] ?? '';

// --- Handle file uploads ---
$validid   = uploadFile('validid', $upload_dir);
$cedula    = uploadFile('cedula', $upload_dir);
$holdingid = uploadFile('holdingid', $upload_dir);

// Validate file uploads
if (!$validid || !$cedula || !$holdingid) {
    echo json_encode(["status" => "error", "message" => "Please upload all required files."]);
    exit;
}

// --- Auto-generate tracking number ---
$tracking_no = strtoupper(uniqid("REQ-"));

// --- Prepare SQL ---
$stmt = $conn->prepare("
    INSERT INTO request (
        tracking_no, firstname, middleinitial, lastname, address, 
        dateofbirth, age, gender, yearresidency, contact, email, 
        purpose, remarks, validid, cedula, holdingid, 
        student_patient_name, student_patient_address, relationship, 
        status, date_submitted
    ) VALUES (
        ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'Pending', NOW()
    )
");

// --- Bind parameters ---
$stmt->bind_param(
    "ssssssisissssssssss",
    $tracking_no, $firstname, $middleinitial, $lastname, $address,
    $dateofbirth, $age, $gender, $yearresidency, $contact, $email,
    $purpose, $remarks, $validid, $cedula, $holdingid,
    $student_patient_name, $student_patient_address, $relationship
);
// --- Execute and respond ---

if ($stmt->execute()) {
    $last_id = $conn->insert_id;
    $tracking_no = "REQ-" . date("Ymd") . "-" . $last_id;

    $update = $conn->prepare("UPDATE request SET tracking_no = ? WHERE id = ?");
    $update->bind_param("si", $tracking_no, $last_id);
    $update->execute();
    $update->close();

    header('Content-Type: application/json');
    echo json_encode([
        "status" => "success",
        "message" => "Request submitted successfully!",
        "tracking_no" => $tracking_no
    ]);
    exit;
}

$stmt->close();
$conn->close();
?>

<?php
session_start();

// ✅ Block unauthorized access
if (empty($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header('Location: login.html?unauthorized=1');
    exit;
}

// ✅ Disable browser caching for this page
header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1
header("Pragma: no-cache"); // HTTP 1.0
header("Expires: 0"); // Proxies
?>


<?php
// ========================================
// DATABASE CONNECTION
// ========================================
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "indigency_db";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// ========================================
// UPDATE STATUS REQUEST (AJAX POST)
// ========================================
if (isset($_POST['update_status'])) {
    $id = intval($_POST['id']);
    $status = $conn->real_escape_string($_POST['status']);
    $pickup_date = isset($_POST['pickup_date']) && $_POST['pickup_date'] !== '' ? $conn->real_escape_string($_POST['pickup_date']) : null;
    $reject_reason = isset($_POST['reject_reason']) && $_POST['reject_reason'] !== '' ? $conn->real_escape_string($_POST['reject_reason']) : null;

    $res = $conn->query("SELECT tracking_no FROM request WHERE id = $id LIMIT 1");
    if ($res && $res->num_rows > 0) {
        $row = $res->fetch_assoc();
        $tracking_no = $conn->real_escape_string($row['tracking_no']);

        $update_fields = ["status='$status'"];
        if ($pickup_date) $update_fields[] = "pickup_date='$pickup_date'";
        if ($reject_reason) $update_fields[] = "reject_reason='" . $reject_reason . "'";
        if ($status === 'Completed') $update_fields[] = "date_claimed=CURDATE()";

        $conn->query("UPDATE request SET " . implode(',', $update_fields) . " WHERE id=$id");

        $stmt = $conn->prepare("INSERT INTO request_history (tracking_no, status) VALUES (?, ?)");
        $stmt->bind_param("ss", $tracking_no, $status);
        $stmt->execute();
        $stmt->close();
    }
    // return minimal response
    echo json_encode(['success' => true]);
    exit;
}

// ========================================
// PAGINATION PER TAB
// ========================================
$limit = 12;
$tab = isset($_GET['tab']) ? $_GET['tab'] : 'pending'; // pending|approved|forpickup|completed|rejected
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$offset = ($page - 1) * $limit;

// Map tab to status string in DB
$statusMap = [
    'pending'   => 'Pending',
    'approved'  => 'Approved',
    'forpickup' => 'For Pick-up',
    'completed' => 'Completed',
    'rejected'  => 'Rejected'
];
$currentStatus = isset($statusMap[$tab]) ? $statusMap[$tab] : 'Pending';

// Count total for this status
$escapedStatus = $conn->real_escape_string($currentStatus);
$totalRes = $conn->query("SELECT COUNT(*) AS total FROM request WHERE status = '$escapedStatus'");
$totalRows = ($totalRes && $totalRes->num_rows) ? intval($totalRes->fetch_assoc()['total']) : 0;
$totalPages = $totalRows > 0 ? ceil($totalRows / $limit) : 1;

// Fetch page rows for this tab
$requestsStmt = $conn->query("
    SELECT * FROM request
    WHERE status = '$escapedStatus'
    ORDER BY date_submitted DESC
    LIMIT $limit OFFSET $offset
");
$all = $requestsStmt ? $requestsStmt->fetch_all(MYSQLI_ASSOC) : [];
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Barangay Balangkas Admin Dashboard</title>
<link rel="stylesheet" href="css/styleAdmin.css">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet">

</head>
<body>

<div class="container">
    <div class="sidebar">
        <img src="images/logo-certigo.png" alt="" class="logo">
        <div class="btns">
            <button onclick="window.location='?tab=pending'">Pending</button>
            <button onclick="window.location='?tab=approved'">Approved</button>
            <button onclick="window.location='?tab=forpickup'">For Pick-up</button>
            <button onclick="window.location='?tab=completed'">Completed</button>
            <button onclick="window.location='?tab=rejected'">Rejected</button>
        </div>
<button id="logoutBtn" class="signOut">Log Out</button>
    </div>

    <div class="main">
        <div id="<?= htmlspecialchars($tab) ?>" class="tab-content">
            <div class="header"><h1><?= ucfirst($tab) ?> Requests</h1></div>

            <!-- Table for each tab -->
            <?php if ($tab === 'pending'): ?>
                <table>
                    <tr><th>Request No.</th><th>Name</th><th>Date</th><th>Action</th></tr>
                    <?php foreach ($all as $r): ?>
                        <tr data-request='<?= htmlspecialchars(json_encode($r), ENT_QUOTES) ?>'>
                            <td><?= htmlspecialchars($r['tracking_no']) ?></td>
                            <td><?= htmlspecialchars($r['firstname'].' '.$r['lastname']) ?></td>
                            <td><?= htmlspecialchars($r['date_submitted']) ?></td>
                            <td>
                                <button class="approve-btn" data-id="<?= intval($r['id']) ?>">Approve</button>
                                <button class="reject-btn" data-id="<?= intval($r['id']) ?>">Reject</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>

            <?php elseif ($tab === 'approved'): ?>
                <table>
                    <tr><th>Request No.</th><th>Name</th><th>Date</th><th>Pick-up Schedule</th><th>Next Step</th></tr>
                    <?php foreach ($all as $r): ?>
                        <tr data-request='<?= htmlspecialchars(json_encode($r), ENT_QUOTES) ?>'>
                            <td><?= htmlspecialchars($r['tracking_no']) ?></td>
                            <td><?= htmlspecialchars($r['firstname'].' '.$r['lastname']) ?></td>
                            <td><?= htmlspecialchars($r['date_submitted']) ?></td>
                            <td>
                                <input type="date" id="pickup_<?= intval($r['id']) ?>" value="<?= htmlspecialchars($r['pickup_date']) ?>">
                            </td>
                            <td>
                                <button class="setup-pickup-btn" data-id="<?= intval($r['id']) ?>">Set-up Pick-up Date</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>

            <?php elseif ($tab === 'forpickup'): ?>
                <table>
                    <tr><th>Request No.</th><th>Name</th><th>Date</th><th>Pick-up Date</th><th>Status</th><th>Complete</th></tr>
                    <?php foreach ($all as $r): ?>
                        <tr data-request='<?= htmlspecialchars(json_encode($r), ENT_QUOTES) ?>'>
                            <td><?= htmlspecialchars($r['tracking_no']) ?></td>
                            <td><?= htmlspecialchars($r['firstname'].' '.$r['lastname']) ?></td>
                            <td><?= htmlspecialchars($r['date_submitted']) ?></td>
                            <td><?= htmlspecialchars($r['pickup_date']) ?></td>
                            <td>
                                <select class="pickup-status" data-id="<?= intval($r['id']) ?>">
                                    <option value="For Claiming" <?= $r['status']=='For Claiming'?'selected':'' ?>>For Claiming</option>
                                    <option value="Claimed" <?= $r['status']=='Claimed'?'selected':'' ?>>Claimed</option>
                                </select>
                            </td>
                            <td><button class="complete-pickup-btn" data-id="<?= intval($r['id']) ?>">✔ Complete</button></td>
                        </tr>
                    <?php endforeach; ?>
                </table>

            <?php elseif ($tab === 'completed'): ?>
                <table>
                    <tr><th>Request No.</th><th>Name</th><th>Date Requested</th><th>Date Claimed</th></tr>
                    <?php foreach ($all as $r): ?>
                        <tr data-request='<?= htmlspecialchars(json_encode($r), ENT_QUOTES) ?>'>
                            <td><?= htmlspecialchars($r['tracking_no']) ?></td>
                            <td><?= htmlspecialchars($r['firstname'].' '.$r['lastname']) ?></td>
                            <td><?= htmlspecialchars($r['date_submitted']) ?></td>
                            <td><?= htmlspecialchars($r['date_claimed']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>

            <?php elseif ($tab === 'rejected'): ?>
                <table>
                    <tr><th>Request No.</th><th>Name</th><th>Date Requested</th><th>Rejection Reason</th></tr>
                    <?php foreach ($all as $r): ?>
                        <tr data-request='<?= htmlspecialchars(json_encode($r), ENT_QUOTES) ?>'>
                            <td><?= htmlspecialchars($r['tracking_no']) ?></td>
                            <td><?= htmlspecialchars($r['firstname'].' '.$r['lastname']) ?></td>
                            <td><?= htmlspecialchars($r['date_submitted']) ?></td>
                            <td><?= htmlspecialchars($r['reject_reason']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php endif; ?>

            <!-- Pagination -->
            <div class="pagination">
                <?php if ($page > 1): ?>
                    <a href="?tab=<?= urlencode($tab) ?>&page=<?= $page - 1 ?>">&laquo; Prev</a>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <a href="?tab=<?= urlencode($tab) ?>&page=<?= $i ?>" class="<?= $i == $page ? 'active' : '' ?>"><?= $i ?></a>
                <?php endfor; ?>

                <?php if ($page < $totalPages): ?>
                    <a href="?tab=<?= urlencode($tab) ?>&page=<?= $page + 1 ?>">Next &raquo;</a>
                <?php endif; ?>
            </div>

        </div> <!-- end tab-content -->
    </div> <!-- end main -->
</div> <!-- end container -->

<!-- Modal container -->
<div id="modal" class="modal">
    <div id="details" class="modal-content"></div>
</div>

<!-- Image preview overlay -->
<div id="imagePreviewOverlay" onclick="this.style.display='none'">
    <img src="" alt="Preview">
</div>

<!-- Logout Confirmation Modal -->
<div id="logoutModal" class="logout-modal" style="display:none;">
  <div class="logout-modal-content">
    <h2>Confirm Logout</h2>
    <p>Are you sure you want to log out?</p>
    <div class="logout-modal-actions">
      <button id="confirmLogout" class="confirm-btn">Yes, Log Out</button>
      <button id="cancelLogout" class="cancel-btn">Cancel</button>
    </div>
  </div>
</div>


<script src="js/scriptAdmin.js"></script>
</body>
</html>

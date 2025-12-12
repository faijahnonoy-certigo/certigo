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

include 'php/config.php';

// ========================================
// UPDATE STATUS REQUEST (AJAX POST)
// ========================================
if (isset($_POST['update_status'])) {
  $id = intval($_POST['id']);
  $status = isset($_POST['status']) ? $conn->real_escape_string($_POST['status']) : '';
  // normalize bad or missing status values coming from client-side
  if ($status === '' || strtolower($status) === 'undefined' || strtolower($status) === 'null') {
    // If pickup_date was provided without explicit status, mark as For Pick-up
    // otherwise default to 'Pending' (not yet approved)
    if (isset($_POST['pickup_date']) && $_POST['pickup_date'] !== '') {
      $status = 'For Pick-up';
    } else {
      $status = 'Pending';
    }
  }
  $pickup_date = isset($_POST['pickup_date']) && $_POST['pickup_date'] !== '' ? $conn->real_escape_string($_POST['pickup_date']) : null;
  $reject_reason = isset($_POST['reject_reason']) && $_POST['reject_reason'] !== '' ? $conn->real_escape_string($_POST['reject_reason']) : null;

  $res = $conn->query("SELECT tracking_no FROM request WHERE id = $id LIMIT 1");
  if ($res && $res->num_rows > 0) {
    $row = $res->fetch_assoc();
    $tracking_no = $conn->real_escape_string($row['tracking_no']);

    $update_fields = ["status='$status'"];
    if ($pickup_date)
      $update_fields[] = "pickup_date='$pickup_date'";
    if ($reject_reason)
      $update_fields[] = "reject_reason='" . $reject_reason . "'";
    if ($status === 'Completed')
      $update_fields[] = "date_claimed=CURDATE()";

    $conn->query("UPDATE request SET " . implode(',', $update_fields) . " WHERE id=$id");

    // Build descriptive history entry including admin username and any reject reason
    $admin_user = isset($_SESSION['admin_username']) ? $conn->real_escape_string($_SESSION['admin_username']) : 'admin';
    $history_status = $status;
    if (!empty($reject_reason)) {
      $history_status .= ' - ' . $reject_reason;
    }
    $history_status .= ' (by ' . $admin_user . ')';

    $stmt = $conn->prepare("INSERT INTO request_history (tracking_no, status) VALUES (?, ?)");
    $stmt->bind_param("ss", $tracking_no, $history_status);
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
  'pending' => 'Pending',
  'approved' => 'Approved',
  'forpickup' => 'For Pick-up',
  'completed' => 'Completed',
  'rejected' => 'Rejected'
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
// Compute counts for sidebar tabs
$tabCounts = [];
// Dashboard count = sum of all 5 defined statuses (consistent with overview)
$dashboardTotal = 0;
foreach ($statusMap as $key => $label) {
  $esc = $conn->real_escape_string($label);
  $r = $conn->query("SELECT COUNT(*) AS c FROM request WHERE status = '$esc'");
  $tabCounts[$key] = ($r && $r->num_rows) ? intval($r->fetch_assoc()['c']) : 0;
  $dashboardTotal += $tabCounts[$key];
}
$tabCounts['dashboard'] = $dashboardTotal;
$rh = $conn->query("SELECT COUNT(*) AS c FROM request_history");
$tabCounts['history'] = ($rh && $rh->num_rows) ? intval($rh->fetch_assoc()['c']) : 0;
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
  <link rel="icon" type="image/png" sizes="32x32" href="images/certigo-favicon.png">

</head>

<body>

  <div class="container">
    <div class="sidebar">
      <img src="images/logo-certigo.png" alt="" class="logo">
      <div class="btns">
        <button class="active-tab" onclick="window.location='?tab=dashboard'">Dashboard <span class="tab-count">(<?= isset($tabCounts['dashboard']) ? $tabCounts['dashboard'] : 0 ?>)</span></button>
        <button class="active-tab" onclick="window.location='?tab=pending'">Pending <span class="tab-count">(<?= isset($tabCounts['pending']) ? $tabCounts['pending'] : 0 ?>)</span></button>
        <button class="active-tab" onclick="window.location='?tab=approved'">Approved <span class="tab-count">(<?= isset($tabCounts['approved']) ? $tabCounts['approved'] : 0 ?>)</span></button>
        <button class="active-tab" onclick="window.location='?tab=forpickup'">For Pick-up <span class="tab-count">(<?= isset($tabCounts['forpickup']) ? $tabCounts['forpickup'] : 0 ?>)</span></button>
        <button class="active-tab" onclick="window.location='?tab=completed'">Completed <span class="tab-count">(<?= isset($tabCounts['completed']) ? $tabCounts['completed'] : 0 ?>)</span></button>
        <button class="active-tab" onclick="window.location='?tab=rejected'">Rejected <span class="tab-count">(<?= isset($tabCounts['rejected']) ? $tabCounts['rejected'] : 0 ?>)</span></button>
        <button class="active-tab" onclick="window.location='?tab=monthly'">Monthly Report</button>
        <button class="active-tab" onclick="window.location='?tab=history'">History</button>
      </div>
      <button id="logoutBtn" class="signOut">Log Out</button>
    </div>

    <div class="main">
      <div id="<?= htmlspecialchars($tab) ?>" class="tab-content">
        <div class="header">
          <h1><?= ucfirst($tab) ?></h1>
        </div>
        <div class="main2">

          <?php if ($tab === 'dashboard'): ?>
            <div class="dashboard-container">

              <!-- HEADER & FILTERS -->
              <div class="dashboard-header">
                <h1>Overview</h1>
                <div class="dashboard-filters">
                  <div class="filter-group">
                    <label for="timeFilter">Filter by:</label>
                    <select id="timeFilter">
                      <option value="month">This Month</option>
                      <option value="week">This Week</option>
                      <option value="day">Today</option>
                      <option value="date">Specific Date</option>
                      <option value="all">All Time</option>
                    </select>
                  </div>
                  <div class="filter-group" id="dateFilterGroup" style="display:none;">
                    <label for="dateFilter">📅 Choose date:</label>
                    <input type="date" id="dateFilter" max="<?= date('Y-m-d') ?>">
                  </div>
                </div>
              </div>

              <!-- SUMMARY CARDS -->
              <section class="dashboard-summary" id="summaryCards">
                <!-- Cards will be dynamically inserted by JS -->
              </section>

              <!-- CHARTS -->
              <section class="dashboard-charts">
                <div class="chart-card">
                  <h3>Requests by Status</h3>
                  <br>
                  <canvas id="statusChart" height="170"></canvas>
                </div>
                <div class="chart-card">
                  <h3>Requests by Purpose</h3>
                  <canvas id="purposeChart" height="50"></canvas>
                </div>
              </section>

              <!-- RECENT REQUESTS -->
              <section class="recent-requests">
                <div class="recent-header">
                  <br>
                  <h3>Recent Requests</h3>
                </div>
                <div class="recent-table-wrapper">
                  <table class="recent-table">
                    <thead class="rr-header">
                      <tr>
                        <th>Tracking No.</th>
                        <th>Name</th>
                        <th>Status</th>
                        <th>Date Submitted</th>
                      </tr>
                    </thead>
                    <thead class="rr-header2">
                      <tr>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>

                      </tr>
                    </thead>
                    <tbody id="recentRequestsBody">
                      <!-- Rows will be dynamically inserted by JS -->
                    </tbody>
                  </table>
                </div>
              </section>

            </div>
          <?php endif; ?>

          <?php if ($tab === 'monthly'): ?>
            <?php
              // determine selected month/year (defaults to current)
              $selMonth = isset($_GET['month']) && !empty($_GET['month']) ? intval($_GET['month']) : intval(date('m'));
              $selYear = isset($_GET['year']) && !empty($_GET['year']) ? intval($_GET['year']) : intval(date('Y'));
              
              // Ensure valid month
              if ($selMonth < 1 || $selMonth > 12) $selMonth = intval(date('m'));
              
              // Calculate first and last day of month
              $startDate = sprintf('%04d-%02d-01', $selYear, $selMonth);
              $endDate = sprintf('%04d-%02d-%02d', $selYear, $selMonth, date('t', strtotime($startDate)));

              $startEsc = $conn->real_escape_string($startDate);
              $endEsc = $conn->real_escape_string($endDate);

              // Total requests for selected month (matching dashboard logic)
              $totalMonthRes = $conn->query("SELECT COUNT(*) AS c FROM request WHERE DATE(date_submitted) BETWEEN '$startEsc' AND '$endEsc'");
              $totalMonth = ($totalMonthRes && $totalMonthRes->num_rows) ? intval($totalMonthRes->fetch_assoc()['c']) : 0;

              // Status counts for month
              $statusCounts = [];
              foreach ($statusMap as $k => $label) {
                $esc = $conn->real_escape_string($label);
                $r = $conn->query("SELECT COUNT(*) AS c FROM request WHERE status = '$esc' AND DATE(date_submitted) BETWEEN '$startEsc' AND '$endEsc'");
                $statusCounts[$label] = ($r && $r->num_rows) ? intval($r->fetch_assoc()['c']) : 0;
              }

              // Purpose counts for month
              $purposeCounts = [];
              $purposeRes = $conn->query("SELECT purpose, COUNT(*) AS c FROM request WHERE DATE(date_submitted) BETWEEN '$startEsc' AND '$endEsc' GROUP BY purpose");
              if ($purposeRes) {
                while ($p = $purposeRes->fetch_assoc()) {
                  $purposeCounts[$p['purpose']] = intval($p['c']);
                }
              }

              // Recent requests for the month
              $monthRequests = [];
              $mr = $conn->query("SELECT * FROM request WHERE DATE(date_submitted) BETWEEN '$startEsc' AND '$endEsc' ORDER BY date_submitted DESC LIMIT 500");
              if ($mr) $monthRequests = $mr->fetch_all(MYSQLI_ASSOC);
            ?>

            <div class="monthly-report">
              <div class="report-header">
                <div>
                  <h2>Monthly Report — <?= date('F', mktime(0,0,0,$selMonth,1,$selYear)) ?> <?= $selYear ?></h2>
                  <form method="GET" style="display:flex;gap:10px;align-items:center;">
                    <input type="hidden" name="tab" value="monthly">
                    <label>Month:
                      <select name="month">
                        <?php for ($m=1;$m<=12;$m++): ?>
                          <option value="<?= $m ?>" <?= $m===$selMonth ? 'selected' : '' ?>><?= date('F', mktime(0,0,0,$m,1,$selYear)) ?></option>
                        <?php endfor; ?>
                      </select>
                    </label>
                    <label>Year:
                      <select name="year">
                        <?php for ($y = date('Y'); $y >= date('Y')-5; $y--): ?>
                          <option value="<?= $y ?>" <?= $y===$selYear ? 'selected' : '' ?>><?= $y ?></option>
                        <?php endfor; ?>
                      </select>
                    </label>
                    <button class="export-btn" type="submit">View</button>
                  </form>
                </div>
                <div class="report-actions">
                  <button class="print-btn" onclick="window.print()">🖨 Print Report</button>
                  <button class="pdf-btn" onclick="downloadMonthlyReportPDF()">📥 Download PDF</button>
                </div>
              </div>

              <div class="report-summary-cards">
                <div class="card">
                  <h3>Total Requests</h3>
                  <p class="big-number"><?= $totalMonth ?></p>
                </div>
                <?php foreach ($statusCounts as $label => $count): ?>
                  <div class="card">
                    <h4><?= htmlspecialchars($label) ?></h4>
                    <p class="big-number"><?= $count ?></p>
                  </div>
                <?php endforeach; ?>
              </div>

              <section class="report-table">
                <h3>Requests (<?= count($monthRequests) ?> shown)</h3>
                <div class="recent-table-wrapper">
                  <table class="recent-table">
                    <thead>
                      <tr>
                        <th>Tracking No.</th>
                        <th>Name</th>
                        <th>Purpose</th>
                        <th>Status</th>
                        <th>Date Submitted</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if (count($monthRequests) > 0): ?>
                        <?php foreach ($monthRequests as $r): ?>
                          <tr>
                            <td><?= htmlspecialchars($r['tracking_no']) ?></td>
                            <td><?= htmlspecialchars($r['firstname'].' '.$r['middleinitial'].' '.$r['lastname']) ?></td>
                            <td><?= htmlspecialchars($r['purpose']) ?></td>
                            <td><?= htmlspecialchars($r['status']) ?></td>
                            <td><?= htmlspecialchars($r['date_submitted']) ?></td>
                          </tr>
                        <?php endforeach; ?>
                      <?php else: ?>
                        <tr class="no-results"><td colspan="5" style="text-align:center;padding:20px;color:#555;">No requests for this month.</td></tr>
                      <?php endif; ?>
                    </tbody>
                  </table>
                </div>
              </section>
            </div>
          <?php endif; ?>

          <br>


          <!------------------------------->
          <!-- PENDING TAB CONTENT -->
          <!------------------------------->
          <?php if ($tab === 'pending'): ?>

          <!-- 🔹 TOOLBAR ABOVE THE TABLE -->
            <div class="table-tools">
              <!-- Search box -->
              <div class="tool-group">
                <input type="text" id="searchInput" class="tool-input"
                  placeholder="🔍 Search by name or request number...">
              </div>

              <!-- Date filter -->
              <div class="tool-group">
                <label for="dateFilter">📅 Date:</label>
                <input type="date" id="dateFilter" class="tool-input">
              </div>

              <!-- Purpose filter -->
              <div class="tool-group">
                <label for="purposeFilterPending">🎯 Purpose:</label>
                <select id="purposeFilterPending" class="tool-input">
                  <option value="">All</option>
                  <option value="Scholarship">Scholarship</option>
                  <option value="Employment">Employment</option>
                  <option value="Educational Assistance">Educational Assistance</option>
                  <option value="Financial Assistance">Financial Assistance</option>
                  <option value="Burial Assistance">Burial Assistance</option>
                  <option value="Medical Assistance">Medical Assistance</option>
                </select>
              </div>

              <!-- Export button -->
              <div class="tool-group">
                <button id="exportExcelBtn" class="export-btn">⬇ Export to Excel</button>
              </div>
            </div>

            <!-- 🔹 TABLE -->
            <table id="pendingTable">
              <tr class="column-header-row">
                <th>Request No.</th>
                <th>Name</th>
                <th>Purpose</th>
                <th>Date Requested</th>
                <th>Action</th>
              </tr>

              <?php foreach ($all as $r): ?>
                <tr data-request='<?= htmlspecialchars(json_encode($r), ENT_QUOTES) ?>' class="request-row">
                  <td><?= htmlspecialchars($r['tracking_no']) ?></td>
                  <td><?= htmlspecialchars($r['firstname'] . ' ' . $r['middleinitial'] . ' ' . $r['lastname']) ?></td>
                  <td><?= htmlspecialchars($r['purpose']) ?></td>
                  <td><?= htmlspecialchars($r['date_submitted']) ?></td>
                  <td>
                    <button class="approve-btn" data-id="<?= intval($r['id']) ?>">Approve</button>
                    <button class="reject-btn" data-id="<?= intval($r['id']) ?>">Reject</button>
                  </td>
                </tr>
              <?php endforeach; ?>
            </table>

                          <?php elseif ($tab === 'history'): ?>

                          <!-- HISTORY TAB -->
                          <div class="table-tools">
                            <div class="tool-group">
                              <input type="text" id="searchHistory" class="tool-input" placeholder="Search history...">
                            </div>
                            <div class="tool-group">
                              <label for="dateFilterHistory">📅 Date:</label>
                              <input type="date" id="dateFilterHistory" class="tool-input">
                            </div>
                          </div>

                          <table id="historyTable">
                            <tr class="column-header-row">
                              <th>Timestamp</th>
                              <th>Action</th>
                              <th>Request No.</th>
                              <th>Name</th>
                            </tr>
                            <?php
                              // fetch recent history entries
                              $historyQuery = "SELECT rh.tracking_no, rh.status, rh.timestamp, r.firstname, r.lastname
                                                FROM request_history rh
                                                LEFT JOIN request r ON rh.tracking_no = r.tracking_no
                                                ORDER BY rh.timestamp DESC
                                                LIMIT 500";
                              $histRes = $conn->query($historyQuery);
                              if ($histRes && $histRes->num_rows > 0) {
                                while ($h = $histRes->fetch_assoc()) {
                            ?>
                              <tr>
                                <td><?= htmlspecialchars($h['timestamp']) ?></td>
                                <td><?= htmlspecialchars($h['status']) ?></td>
                                <td><?= htmlspecialchars($h['tracking_no']) ?></td>
                                <td><?= htmlspecialchars(trim(($h['firstname'] ?? '') . ' ' . ($h['lastname'] ?? ''))) ?></td>
                              </tr>
                            <?php
                                }
                              } else {
                            ?>
                              <tr class="no-results"><td colspan="4" style="text-align:center;padding:20px;color:#555;">No action history available.</td></tr>
                            <?php } ?>
                          </table>


          <!------------------------------->
          <!-- APPROVED TAB CONTENT -->
          <!------------------------------->
          <?php elseif ($tab === 'approved'): ?>

            <!-- TOOLBAR -->
            <div class="table-tools">
              <div class="tool-group">
                <input type="text" id="searchInputApproved" class="tool-input"
                  placeholder="Search by name or request number...">
              </div>

              <!-- Date filter -->
              <div class="tool-group">
                <label for="dateFilter">📅 Date:</label>
                <input type="date" id="dateFilter" class="tool-input">
              </div>


              <!-- Purpose filter -->
              <div class="tool-group">
                <label for="purposeFilterApproved">🎯 Purpose:</label>
                <select id="purposeFilterApproved" class="tool-input">
                  <option value="">All</option>
                  <option value="Scholarship">Scholarship</option>
                  <option value="Employment">Employment</option>
                  <option value="Educational Assistance">Educational Assistance</option>
                  <option value="Financial Assistance">Financial Assistance</option>
                  <option value="Burial Assistance">Burial Assistance</option>
                  <option value="Medical Assistance">Medical Assistance</option>
                </select>
              </div>

              <div class="tool-group">
                <button id="exportExcelApproved" class="export-btn">⬇ Export to Excel</button>
              </div>
            </div>

            <table id="approvedTable">
              <tr class="column-header-row">
                <th>Request No.</th>
                <th>Name</th>
                <th>Purpose</th>
                <th>Date Requested</th>
                <th>Pick-up Schedule</th>
                <th>Next Step</th>
              </tr>
              <?php foreach ($all as $r): ?>
                <tr data-request='<?= htmlspecialchars(json_encode($r), ENT_QUOTES) ?>' class="request-row">
                  <td><?= htmlspecialchars($r['tracking_no']) ?></td>
                  <td><?= htmlspecialchars($r['firstname'] . ' ' . $r['middleinitial'] . ' ' . $r['lastname']) ?></td>
                  <td><?= htmlspecialchars($r['purpose']) ?></td>
                  <td><?= htmlspecialchars($r['date_submitted']) ?></td>
                  <td><input type="date" id="pickup_<?= intval($r['id']) ?>"
                      value="<?= htmlspecialchars($r['pickup_date']) ?>"></td>
                  <td><button class="setup-pickup-btn" data-id="<?= intval($r['id']) ?>">Set-up Pick-up Date</button></td>
                </tr>
              <?php endforeach; ?>
            </table>



          <!------------------------------->
          <!-- FOR PICKUP TAB CONTENT -->
          <!------------------------------->

          <?php elseif ($tab === 'forpickup'): ?>

            <div class="table-tools">
              <div class="tool-group">
                <input type="text" id="searchInputForPickup" class="tool-input"
                  placeholder="🔍 Search by name or request number...">
              </div>

             <div class="tool-group">
                <label for="dateFilterForPickup">📅 Date of Pickup:</label>
                <input type="date" id="dateFilterForPickup" class="tool-input">
              </div>

              <!-- Purpose filter -->
              <!-- Purpose filter -->
              <div class="tool-group">
                <label for="purposeFilterForPickup">🎯 Purpose:</label>
                <select id="purposeFilterForPickup" class="tool-input">
                  <option value="">All</option>
                  <option value="Scholarship">Scholarship</option>
                  <option value="Employment">Employment</option>
                  <option value="Educational Assistance">Educational Assistance</option>
                  <option value="Financial Assistance">Financial Assistance</option>
                  <option value="Burial Assistance">Burial Assistance</option>
                  <option value="Medical Assistance">Medical Assistance</option>
                </select>
              </div>

              <div class="tool-group">
                <button id="exportExcelForPickup" class="export-btn">⬇ Export to Excel</button>
              </div>
            </div>

            <table id="forpickupTable">
              <tr class="column-header-row">
                <th>Request No.</th>
                <th>Name</th>
                <th>Purpose</th>
                <th>Date Requested</th>
                <th>Pick-up Date</th>
                <th>Status</th>
                <th>Complete</th>
              </tr>
              <?php foreach ($all as $r): ?>
                <tr data-request='<?= htmlspecialchars(json_encode($r), ENT_QUOTES) ?>' class="request-row">
                  <td><?= htmlspecialchars($r['tracking_no']) ?></td>
                  <td><?= htmlspecialchars($r['firstname'] . ' ' . $r['middleinitial'] . ' ' . $r['lastname']) ?></td>
                  <td><?= htmlspecialchars($r['purpose']) ?></td>

                  <td><?= htmlspecialchars($r['date_submitted']) ?></td>
                  <td><?= htmlspecialchars($r['pickup_date']) ?></td>
                  <td>
                    <select class="pickup-status" data-id="<?= intval($r['id']) ?>">
                      <option value="For Claiming" <?= $r['status'] == 'For Claiming' ? 'selected' : '' ?>>For Claiming
                      </option>
                      <option value="Claimed" <?= $r['status'] == 'Claimed' ? 'selected' : '' ?>>Claimed</option>
                    </select>
                  </td>
                  <td><button class="complete-pickup-btn" data-id="<?= intval($r['id']) ?>">✔ Complete</button></td>
                </tr>
              <?php endforeach; ?>
            </table>


          <?php elseif ($tab === 'completed'): ?>

            <div class="table-tools">
              <div class="tool-group">
                <input type="text" id="searchInputCompleted" class="tool-input"
                  placeholder="🔍 Search by name or request number...">
              </div>

              <div class="tool-group">
                <label for="dateFilterCompleted">📅 Date Claimed:</label>
                <input type="date" id="dateFilterCompleted" class="tool-input">
              </div>

              <!-- Purpose filter -->
              <!-- Purpose filter -->
              <div class="tool-group">
                <label for="purposeFilterCompleted">🎯 Purpose:</label>
                <select id="purposeFilterCompleted" class="tool-input">
                  <option value="">All</option>
                  <option value="Scholarship">Scholarship</option>
                  <option value="Employment">Employment</option>
                  <option value="Educational Assistance">Educational Assistance</option>
                  <option value="Financial Assistance">Financial Assistance</option>
                  <option value="Burial Assistance">Burial Assistance</option>
                  <option value="Medical Assistance">Medical Assistance</option>
                </select>
              </div>

              <div class="tool-group">
                <button id="exportExcelCompleted" class="export-btn">⬇ Export to Excel</button>
              </div>
            </div>

            <table id="completedTable">
              <tr class="column-header-row">
                <th>Request No.</th>
                <th>Name</th>
                <th>Purpose</th>
                <th>Date Requested</th>
                <th>Date Claimed</th>
              </tr>
              <?php foreach ($all as $r): ?>
                <tr data-request='<?= htmlspecialchars(json_encode($r), ENT_QUOTES) ?>' class="request-row">
                  <td><?= htmlspecialchars($r['tracking_no']) ?></td>
                  <td><?= htmlspecialchars($r['firstname'] . ' ' . $r['middleinitial'] . ' ' . $r['lastname']) ?></td>
                  <td><?= htmlspecialchars($r['purpose']) ?></td>

                  <td><?= htmlspecialchars($r['date_submitted']) ?></td>
                  <td><?= htmlspecialchars($r['date_claimed']) ?></td>
                </tr>
              <?php endforeach; ?>
            </table>

          <?php elseif ($tab === 'rejected'): ?>

            <div class="table-tools">
              <div class="tool-group">
                <input type="text" id="searchInputRejected" class="tool-input"
                  placeholder="🔍 Search by name or request number...">
              </div>

              <div class="tool-group">
                <label for="dateFilterRejected">📅 Date Rejected:</label>
                <input type="date" id="dateFilterRejected" class="tool-input">
              </div>

              <!-- rejection filter -->
              <!-- HTML -->
              <div class="tool-group">
                <label for="rejectedFilter">🎯 Rejection Reason:</label>
                <select id="rejectedFilter" class="tool-input">
                  <option value="">All</option>
                  <option value="Incomplete requirements">Incomplete requirements</option>
                  <option value="Invalid or unclear ID/image provided">Invalid or unclear ID/image provided</option>
                  <option value="Incorrect personal information">Incorrect personal information</option>
                  <option value="Duplicate request submitted">Duplicate request submitted</option>
                  <option value="Not eligible for this document">Not eligible for this document</option>
                  <option value="Pending verification from barangay">Pending verification from barangay</option>
                </select>
              </div>


              <div class="tool-group">
                <button id="exportExcelRejected" class="export-btn">⬇ Export to Excel</button>
              </div>
            </div>

            <table id="rejectedTable">
              <tr class="column-header-row">
                <th>Request No.</th>
                <th>Name</th>
                <th>Date Requested</th>
                <th>Date Rejected</th>
                <th>Rejection Reason</th>
              </tr>
              <?php foreach ($all as $r): ?>
                <?php
                $reject_date = null;
                $stmt = $conn->prepare("
        SELECT timestamp FROM request_history 
        WHERE tracking_no = ? AND status = 'Rejected' 
        ORDER BY timestamp DESC LIMIT 1
    ");
                $stmt->bind_param('s', $r['tracking_no']);
                $stmt->execute();
                $result = $stmt->get_result();
                if ($row = $result->fetch_assoc()) {
                  $reject_date = $row['timestamp'];
                }
                $stmt->close();
                ?>
                <tr data-request='<?= htmlspecialchars(json_encode($r), ENT_QUOTES) ?>' class="request-row">
                  <td><?= htmlspecialchars($r['tracking_no']) ?></td>
                  <td><?= htmlspecialchars($r['firstname'] . ' ' . $r['lastname']) ?></td>
                  <td><?= htmlspecialchars($r['date_submitted']) ?></td>
                  <td><?= $reject_date ? htmlspecialchars($reject_date) : '—' ?></td>
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
              <a href="?tab=<?= urlencode($tab) ?>&page=<?= $i ?>"
                class="<?= $i == $page ? 'active' : '' ?>"><?= $i ?></a>
            <?php endfor; ?>

            <?php if ($page < $totalPages): ?>
              <a href="?tab=<?= urlencode($tab) ?>&page=<?= $page + 1 ?>">Next &raquo;</a>
            <?php endif; ?>
          </div>

        </div> <!-- end tab-content -->
      </div>
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
        <button id="confirmLogout" class="confirm-btn">Yes</button>
        <button id="cancelLogout" class="cancel-btn">Cancel</button>
      </div>
    </div>
  </div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

  <script>
    // Server-provided status counts to keep overview consistent with tab counts
    window.serverStatusCounts = <?= json_encode([
      'Pending' => isset($tabCounts['pending']) ? $tabCounts['pending'] : 0,
      'Approved' => isset($tabCounts['approved']) ? $tabCounts['approved'] : 0,
      'For Pick-up' => isset($tabCounts['forpickup']) ? $tabCounts['forpickup'] : 0,
      'Completed' => isset($tabCounts['completed']) ? $tabCounts['completed'] : 0,
      'Rejected' => isset($tabCounts['rejected']) ? $tabCounts['rejected'] : 0
    ]) ?>;

    // Function to download monthly report as PDF
    function downloadMonthlyReportPDF() {
      const element = document.querySelector('.monthly-report');
      if (!element) {
        alert('Monthly report not found!');
        return;
      }
      
      const month = document.querySelector('select[name="month"]').options[document.querySelector('select[name="month"]').selectedIndex].text;
      const year = document.querySelector('select[name="year"]').value;
      const filename = `Monthly-Report-${month}-${year}.pdf`;

      const opt = {
        margin: 10,
        filename: filename,
        image: { type: 'jpeg', quality: 0.98 },
        html2canvas: { scale: 2 },
        jsPDF: { orientation: 'portrait', unit: 'mm', format: 'a4' }
      };

      html2pdf().set(opt).from(element).save();
    }
  </script>

  <script src="js/scriptAdmin.js"></script>

  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</body>

</html>
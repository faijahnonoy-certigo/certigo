document.addEventListener('DOMContentLoaded', function() {

    // ==============================
    // Tab switching
    // ==============================
    window.changeTab = function(tab) {
        document.querySelectorAll('.tab-content').forEach(t => t.style.display = 'none');
        document.getElementById(tab).style.display = 'block';
    };

    // ==============================
    // AJAX status update
    // ==============================
    window.updateStatus = function(id, status, pickup_date = null, reject_reason = null) {
        if (!confirm('Are you sure you want to mark this as ' + status + '?')) return;
        const formData = new FormData();
        formData.append('update_status', true);
        formData.append('id', id);
        formData.append('status', status);
        if (pickup_date) formData.append('pickup_date', pickup_date);
        if (reject_reason) formData.append('reject_reason', reject_reason);

        fetch('../admin/update_pickup.php', { method: 'POST', body: formData })
    .then(res => res.json())
    .then(data => {
        if(data.status === 'success'){
            alert(data.message);
            location.reload();
        } else {
            alert(data.message);
        }
    })
    .catch(err => console.error(err));

    };

    // ==============================
    // Buttons: approve / reject / etc.
    // ==============================
    document.querySelectorAll('.approve-btn').forEach(btn => {
        btn.addEventListener('click', e => {
            e.stopPropagation();
            updateStatus(btn.dataset.id, 'Approved');
        });
    });

    document.querySelectorAll('.reject-btn').forEach(btn => {
        btn.addEventListener('click', e => {
            e.stopPropagation();
            promptReject(btn.dataset.id);
        });
    });

    document.querySelectorAll('.setup-pickup-btn').forEach(btn => {
        btn.addEventListener('click', e => {
            e.stopPropagation();
            const date = document.getElementById('pickup_' + btn.dataset.id).value;
            if (!date) { alert('Please select a date'); return; }
            updateStatus(btn.dataset.id, 'For Pick-up', date);
        });
    });

    document.querySelectorAll('.complete-pickup-btn').forEach(btn => {
        btn.addEventListener('click', e => {
            e.stopPropagation();
            const select = document.querySelector(`.pickup-status[data-id="${btn.dataset.id}"]`);
            if (select.value !== 'Claimed') { alert('Status must be "Claimed" before completing.'); return; }
            updateStatus(btn.dataset.id, 'Completed');
        });
    });

    // ==============================
    // Row click to open modal
    // ==============================
    document.querySelectorAll('tr[data-request]').forEach(tr => {
        tr.addEventListener('click', e => {
            if (['BUTTON', 'INPUT', 'SELECT'].includes(e.target.tagName)) return;
            const data = JSON.parse(tr.dataset.request);
            showDetails(data);
        });
    });

    // ==============================
    // Show modal with request details
    // ==============================
    window.showDetails = function(data) {
        document.getElementById('modal').style.display = 'block';
        let buttons = '';
        if (data.status === 'Pending') {
            buttons = `
                       <button onclick="promptReject(${data.id})">Reject</button>`;
        } else if (data.status === 'Approved') {
            buttons = `<button onclick="promptReject(${data.id})">Reject</button>`;
        }

        const makeImg = (file, label) => file 
            ? `<p><b>${label}:</b><br><img src="php/uploads/${file}" alt="${label}" class="preview-img"></p>` 
            : '';

        document.getElementById('details').innerHTML = `
    <h2>${data.firstname} ${data.middleinitial || ''} ${data.lastname}</h2>
    <p><b>Tracking No:</b> ${data.tracking_no}</p>
    <p><b>Address:</b> ${data.address || 'N/A'}</p>
    <p><b>Date of Birth:</b> ${data.dateofbirth || 'N/A'}</p>
    <p><b>Age:</b> ${data.age || 'N/A'}</p>
    <p><b>Gender:</b> ${data.gender || 'N/A'}</p>
    <p><b>Years of Residency:</b> ${data.yearresidency || 'N/A'}</p>
    <p><b>Contact:</b> ${data.contact || 'N/A'}</p>
    <p><b>Email:</b> ${data.email || 'N/A'}</p>
    <p><b>Purpose:</b> ${data.purpose || 'N/A'}</p>
    <p><b>Remarks:</b> ${data.remarks || 'None'}</p>
    <p><b>Status:</b> ${data.status}</p>
    <p><b>Student/Patient Name:</b> ${data.student_patient_name || 'N/A'}</p>
    <p><b>Student/Patient Address:</b> ${data.student_patient_address || 'N/A'}</p>
    <p><b>Relationship:</b> ${data.relationship || 'N/A'}</p>
    <p><b>Pickup Date:</b> ${data.pickup_date || 'N/A'}</p>
    <p><b>Date Claimed:</b> ${data.date_claimed || 'N/A'}</p>
    <p><b>Reject Reason:</b> ${data.reject_reason || 'N/A'}</p>
    ${makeImg(data.validid, 'Valid ID')}
    ${makeImg(data.cedula, 'Cedula')}
    ${makeImg(data.holdingid, 'Holding ID')}
    <div>${buttons}<button onclick="closeModal()">Close</button></div>
`;


        document.querySelectorAll('.preview-img').forEach(img => {
            img.style.width = '100px';
            img.style.height = '100px';
            img.style.objectFit = 'cover';
            img.style.cursor = 'pointer';
            img.style.border = '1px solid #ccc';
            img.style.borderRadius = '4px';
            img.style.margin = '5px';

            img.addEventListener('click', function(e) {
                e.stopPropagation();
                const overlay = document.getElementById('imagePreviewOverlay');
                overlay.querySelector('img').src = this.src;
                overlay.style.display = 'flex';
            });
        });
    };

    // ==============================
    // Close modal
    // ==============================
    window.closeModal = function() {
        document.getElementById('modal').style.display = 'none';
    };

    // ==============================
    // Reject reason dropdown modal
    // ==============================
    const REJECTION_REASONS = [
        "Incomplete requirements",
        "Invalid or unclear ID/image provided",
        "Incorrect personal information",
        "Duplicate request submitted",
        "Not eligible for this document",
        "Pending verification from barangay"
    ];

    window.promptReject = function(id) {
        const popupBg = document.createElement('div');
        popupBg.className = 'reject-bg';
        popupBg.innerHTML = `
            <div class="reject-box">
                <h3>Select Rejection Reason</h3>
                <select id="rejectReasonSelect">
                    <option value="" disabled selected>Select a reason</option>
                    ${REJECTION_REASONS.map(r => `<option value="${r}">${r}</option>`).join('')}
                </select>
                <div class="reject-actions">
                    <button id="confirmReject">Confirm</button>
                    <button id="cancelReject">Cancel</button>
                </div>
            </div>
        `;
        document.body.appendChild(popupBg);
        popupBg.style.display = 'flex';

        popupBg.querySelector('#confirmReject').addEventListener('click', () => {
            const select = document.getElementById('rejectReasonSelect');
            const reason = select.value;
            if (!reason) {
                alert('Please select a rejection reason.');
                return;
            }
            updateStatus(id, 'Rejected', null, reason);
            popupBg.remove();
        });

        popupBg.querySelector('#cancelReject').addEventListener('click', () => popupBg.remove());
        popupBg.addEventListener('click', (e) => {
            if (e.target === popupBg) popupBg.remove();
        });
    };
});


// ==============================
// Logout modal
// ==============================
document.addEventListener("DOMContentLoaded", function () {
    const logoutBtn = document.getElementById("logoutBtn");
    const logoutModal = document.getElementById("logoutModal");
    const confirmLogout = document.getElementById("confirmLogout");
    const cancelLogout = document.getElementById("cancelLogout");

    if (!logoutBtn || !logoutModal) return;

    logoutBtn.addEventListener("click", function (e) {
        e.preventDefault();
        logoutModal.style.display = "flex";
    });

    cancelLogout.addEventListener("click", function () {
        logoutModal.style.display = "none";
    });

    confirmLogout.addEventListener("click", function () {
        window.location.href = "php/logout.php";
    });

    window.addEventListener("click", function (e) {
        if (e.target === logoutModal) {
            logoutModal.style.display = "none";
        }
    });
});


document.querySelectorAll('.request-row').forEach(row => {
  row.addEventListener('click', () => {
    document.querySelectorAll('.request-row').forEach(r => r.classList.remove('active'));
    row.classList.add('active');
  });
});

document.querySelectorAll('.active-tab').forEach(btn => {
  btn.addEventListener('click', () => {
    document.querySelectorAll('.active-tab').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
  });
});


function setupTableFeatures(
  tableId,
  searchId,
  dateId,
  purposeId = null,
  exportId = null,
  dateColumnIndex = 2,
  purposeColumnIndex = 2,
  rejectedId = null,
  rejectedColumnIndex = null
) {
  const table = document.getElementById(tableId);
  if (!table) return;

  const rows = table.querySelectorAll('.request-row');

  function filterRows() {
    const searchTerm = searchId ? document.getElementById(searchId)?.value.toLowerCase() : '';
    const dateTerm = dateId ? document.getElementById(dateId)?.value : '';
    const purposeTerm = purposeId ? document.getElementById(purposeId)?.value.toLowerCase() : '';
    const rejectTerm = rejectedId ? document.getElementById(rejectedId)?.value.toLowerCase() : '';

    rows.forEach(row => {
      const dateCell = dateColumnIndex != null ? (row.cells[dateColumnIndex]?.innerText.split(' ')[0] || '') : '';
      const purposeCell = purposeColumnIndex != null ? (row.cells[purposeColumnIndex]?.innerText.toLowerCase() || '') : '';
      const rejectCell = rejectedColumnIndex != null ? (row.cells[rejectedColumnIndex]?.innerText.toLowerCase() || '') : '';

      const matchesSearch = !searchTerm || row.innerText.toLowerCase().includes(searchTerm);
      const matchesDate = !dateTerm || dateCell === dateTerm;
      const matchesPurpose = !purposeTerm || purposeCell === purposeTerm;
      const matchesReject = !rejectTerm || rejectCell === rejectTerm;

      row.style.display = matchesSearch && matchesDate && matchesPurpose && matchesReject ? '' : 'none';
    });
  }

  // Add listeners
  if (searchId) document.getElementById(searchId)?.addEventListener('input', filterRows);
  if (dateId) document.getElementById(dateId)?.addEventListener('change', filterRows);
  if (purposeId) document.getElementById(purposeId)?.addEventListener('change', filterRows);
  if (rejectedId) document.getElementById(rejectedId)?.addEventListener('change', filterRows);

  // Export to Excel
  if (exportId) {
    const exportBtn = document.getElementById(exportId);
    exportBtn?.addEventListener('click', () => {
      const wb = XLSX.utils.table_to_book(table, { sheet: tableId });
      XLSX.writeFile(wb, tableId + ".xlsx");
    });
  }
}


// ===== Setup all tables with unique filter IDs =====
setupTableFeatures('pendingTable', 'searchInput', 'dateFilter', 'purposeFilterPending', 'exportExcelBtn', 3, 2);
setupTableFeatures('approvedTable', 'searchInputApproved', 'dateFilter', 'purposeFilterApproved', 'exportExcelApproved', 3, 2);
setupTableFeatures('forpickupTable', 'searchInputForPickup', 'dateFilterForPickup', 'purposeFilterForPickup', 'exportExcelForPickup', 4, 2);
setupTableFeatures('completedTable', 'searchInputCompleted', 'dateFilterCompleted', 'purposeFilterCompleted', 'exportExcelCompleted', 4, 2);
setupTableFeatures('rejectedTable', 'searchInputRejected', 'dateFilterRejected', null, 'exportExcelRejected', 3, null, 'rejectedFilter', 4);

const summaryCardsContainer = document.getElementById('summaryCards');
const statusChartCtx = document.getElementById('statusChart').getContext('2d');
const purposeChartCtx = document.getElementById('purposeChart').getContext('2d');
const recentBody = document.getElementById('recentRequestsBody');

let statusChart, purposeChart;

// Fetch & render dashboard data
async function fetchDashboardData() {
  const time = document.getElementById('timeFilter').value;
  const date = document.getElementById('dateFilter').value;
  const params = new URLSearchParams({ time, date });

  const res = await fetch(`php/get_dashboard_data.php?${params.toString()}`);
  const data = await res.json();

  updateSummaryCards(data.status);
  renderCharts(data);
  updateRecentRequests(time, date);
  
}

// Update summary cards
function updateSummaryCards(statusData) {
  const total = Object.values(statusData).reduce((a,b) => a+b, 0);
  summaryCardsContainer.innerHTML = `
    <div class="summary-card total"><h3>Total</h3><p>${total}</p></div>
    ${Object.entries(statusData).map(([s,v]) => `
      <div class="summary-card ${s.toLowerCase().replace(/\s/g,'-')}">
        <h3>${s}</h3><p>${v}</p>
      </div>
    `).join('')}
  `;
}

// Render charts
function renderCharts(data) {
  if (statusChart) statusChart.destroy();
  if (purposeChart) purposeChart.destroy();

  statusChart = new Chart(statusChartCtx, {
    type: 'bar',
    data: {
      labels: Object.keys(data.status),
      datasets: [{ label:'Requests by Status', data:Object.values(data.status),
        backgroundColor:['#ff9800','#4caf50','#2196f3','#673ab7','#f44336'] }]
    },
    options: { responsive:true, plugins:{legend:{display:false}} }
  });

  purposeChart = new Chart(purposeChartCtx, {
    type:'doughnut',
    data: {
      labels:Object.keys(data.purpose),
      datasets:[{ data:Object.values(data.purpose),
        backgroundColor:['#4caf50','#2196f3','#ff9800','#9c27b0','#f44336','#00bcd4'] }]
    },
    options:{ plugins:{ title:{ display:true, text:'Requests by Purpose' } } }
  });
}

// Fetch recent requests dynamically
async function updateRecentRequests(time, date) {
  const params = new URLSearchParams({ time, date });
  const res = await fetch(`php/get_recent_requests.php?${params.toString()}`);
  const data = await res.json();

  recentBody.innerHTML = data.map(r => `
    <tr>
      <td>${r.tracking_no}</td>
      <td>${r.firstname} ${r.lastname}</td>
      <td class="status ${r.status.toLowerCase().replace(/\s/g,'-')}">${r.status}</td>
      <td>${r.date_submitted}</td>
    </tr>
  `).join('');
}

// Filter events
document.getElementById('timeFilter').addEventListener('change', () => {
  const val = document.getElementById('timeFilter').value;
  document.getElementById('dateFilterGroup').style.display = (val==='date')?'flex':'none';
  fetchDashboardData();
});

document.getElementById('dateFilter').addEventListener('change', fetchDashboardData);

// Initial load
fetchDashboardData();

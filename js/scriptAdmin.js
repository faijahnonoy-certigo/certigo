document.addEventListener('DOMContentLoaded', function() {

    // ==============================
    // Modal Alert & Confirm Functions
    // ==============================
    window.modalAlert = function(message, callback = null) {
        const modalBg = document.createElement('div');
        modalBg.style.cssText = `
            position: fixed; top: 0; left: 0;
            width: 100%; height: 100%;
            background: rgba(0,0,0,0.6);
            display: flex; justify-content: center; align-items: center;
            z-index: 9999;
        `;
        
        const modalBox = document.createElement('div');
        modalBox.style.cssText = `
            background: white; padding: 25px 30px; border-radius: 12px;
            width: 90%; max-width: 400px; text-align: center;
            transform: scale(0); opacity: 0;
            animation: popupIn 0.25s forwards;
        `;
        modalBox.innerHTML = `
            <p>${message}</p>
            <div style="margin-top:20px; display:flex; justify-content:center;">
                <button id="alertOk" style="background:#2196F3;color:white;padding:10px 30px;border:none;border-radius:6px;cursor:pointer;">OK</button>
            </div>
        `;
        
        modalBg.appendChild(modalBox);
        document.body.appendChild(modalBg);
        
        const styleSheet = document.createElement("style");
        styleSheet.innerHTML = `
        @keyframes popupIn {
            0% { transform: scale(0.8); opacity: 0; }
            60% { transform: scale(1.05); opacity: 1; }
            100% { transform: scale(1); opacity: 1; }
        }`;
        if (!document.querySelector("style[data-modal]")) {
            styleSheet.setAttribute("data-modal", "1");
            document.head.appendChild(styleSheet);
        }
        
        modalBg.querySelector('#alertOk').addEventListener('click', () => {
            modalBg.remove();
            if (callback) callback();
        });
        
        modalBg.addEventListener('click', e => {
            if(e.target === modalBg) {
                modalBg.remove();
                if (callback) callback();
            }
        });
    };

    window.modalConfirm = function(message, onYes = null, onNo = null) {
        const modalBg = document.createElement('div');
        modalBg.style.cssText = `
            position: fixed; top: 0; left: 0;
            width: 100%; height: 100%;
            background: rgba(0,0,0,0.6);
            display: flex; justify-content: center; align-items: center;
            z-index: 9999;
        `;
        
        const modalBox = document.createElement('div');
        modalBox.style.cssText = `
            background: white; padding: 25px 30px; border-radius: 12px;
            width: 90%; max-width: 400px; text-align: center;
            transform: scale(0); opacity: 0;
            animation: popupIn 0.25s forwards;
        `;
        modalBox.innerHTML = `
            <p>${message}</p>
            <div style="margin-top:20px; display:flex; justify-content:center; gap:15px;">
                <button id="confirmYes" style="background:#4CAF50;color:white;padding:10px 22px;border:none;border-radius:6px;cursor:pointer;">Yes</button>
                <button id="confirmNo" style="background:#777;color:white;padding:10px 22px;border:none;border-radius:6px;cursor:pointer;">No</button>
            </div>
        `;
        
        modalBg.appendChild(modalBox);
        document.body.appendChild(modalBg);
        
        const styleSheet = document.createElement("style");
        styleSheet.innerHTML = `
        @keyframes popupIn {
            0% { transform: scale(0.8); opacity: 0; }
            60% { transform: scale(1.05); opacity: 1; }
            100% { transform: scale(1); opacity: 1; }
        }`;
        if (!document.querySelector("style[data-modal]")) {
            styleSheet.setAttribute("data-modal", "1");
            document.head.appendChild(styleSheet);
        }
        
        modalBg.querySelector('#confirmYes').addEventListener('click', () => {
            modalBg.remove();
            if (onYes) onYes();
        });
        
        modalBg.querySelector('#confirmNo').addEventListener('click', () => {
            modalBg.remove();
            if (onNo) onNo();
        });
        
        modalBg.addEventListener('click', e => {
            if(e.target === modalBg) {
                modalBg.remove();
                if (onNo) onNo();
            }
        });
    };

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
        modalConfirm('Are you sure you want to mark this as ' + status + '?', function() {
            const formData = new FormData();
            formData.append('update_status', true);
            formData.append('id', id);
            formData.append('status', status);
            if (pickup_date) formData.append('pickup_date', pickup_date);
            if (reject_reason) formData.append('reject_reason', reject_reason);

            fetch('', { method: 'POST', body: formData })
                .then(() => location.reload());
        });
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
            if (!date) { 
                modalAlert('Please select a date');
                return; 
            }
            updateStatus(btn.dataset.id, 'For Pick-up', date);
        });
    });

    document.querySelectorAll('.complete-pickup-btn').forEach(btn => {
        btn.addEventListener('click', e => {
            e.stopPropagation();
            const select = document.querySelector(`.pickup-status[data-id="${btn.dataset.id}"]`);
            if (select.value !== 'Claimed') { 
                modalAlert('Status must be "Claimed" before completing.');
                return; 
            }
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
        // normalize status for display and button logic
        const status = (data.status && String(data.status).toLowerCase() !== 'undefined') ? data.status : 'Pending';
        let buttons = '';
        if (status === 'Pending') {
          buttons = `
                 <button class="btn-reject" onclick="promptReject(${data.id})">Reject</button>`;
        } else if (status === 'Approved') {
          buttons = `<button class="btn-reject" onclick="promptReject(${data.id})">Reject</button>`;
        }

        const makeImg = (file, label) => file 
            ? `<p><b>${label}:</b><br><img src="php/uploads/${file}" alt="${label}" class="preview-img"></p>` 
            : '';

        document.getElementById('details').innerHTML = `
    <h2>${data.firstname} ${data.middleinitial || ''} ${data.lastname}</h2>
    <p><b>Tracking No:</b> ${data.tracking_no}</p>
        <p><b>Preferred Pick-up Date (user):</b> ${data.preferred_pickup_date || 'N/A'}</p>
    <p><b>Address:</b> ${data.address || 'N/A'}</p>
    <p><b>Date of Birth:</b> ${data.dateofbirth || 'N/A'}</p>
    <p><b>Age:</b> ${data.age || 'N/A'}</p>
    <p><b>Gender:</b> ${data.gender || 'N/A'}</p>
    <p><b>Years of Residency:</b> ${data.yearresidency || 'N/A'}</p>
    <p><b>Contact:</b> ${data.contact || 'N/A'}</p>
    <p><b>Email:</b> ${data.email || 'N/A'}</p>
    <p><b>Purpose:</b> ${data.purpose || 'N/A'}</p>
    <p><b>Remarks:</b> ${data.remarks || 'None'}</p>
    <p><b>Status:</b> ${status}</p>
    <p><b>Student/Patient Name:</b> ${data.student_patient_name || 'N/A'}</p>
    <p><b>Student/Patient Address:</b> ${data.student_patient_address || 'N/A'}</p>
    <p><b>Relationship:</b> ${data.relationship || 'N/A'}</p>
    <p><b>Pickup Date:</b> ${data.pickup_date || 'N/A'}</p>
    <p><b>Date Claimed:</b> ${data.date_claimed || 'N/A'}</p>
    <p><b>Reject Reason:</b> ${data.reject_reason || 'N/A'}</p>
    ${makeImg(data.validid, 'Valid ID')}
    ${makeImg(data.cedula, 'Cedula')}
    ${makeImg(data.holdingid, 'Holding ID')}
    <div class="modal-actions">${buttons}<button onclick="closeModal()" class="btn-close">Close</button></div>
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
    
    //APPROVE
    
    window.promptApprove = function(id, status) {
    // Create background div
    const modalBg = document.createElement('div');
    modalBg.className = 'modal-bg';
    modalBg.style.cssText = `
        position: fixed; top: 0; left: 0;
        width: 100%; height: 100%;
        background: rgba(0,0,0,0.6);
        display: flex; justify-content: center; align-items: center;
        z-index: 9999;
    `;

    // Create modal box
    const modalBox = document.createElement('div');
    modalBox.style.cssText = `
        background: white; padding: 25px 30px; border-radius: 12px;
        width: 90%; max-width: 400px; text-align: center;
        transform: scale(0); opacity: 0;
        animation: popupIn 0.25s forwards;
    `;
    modalBox.innerHTML = `
        <h3>Are you sure you want to mark this as ${status}?</h3>
        <div style="margin-top:20px; display:flex; justify-content:center; gap:15px;">
            <button id="modalYes" style="background:#4CAF50;color:white;padding:10px 22px;border:none;border-radius:6px;cursor:pointer;">Yes</button>
            <button id="modalNo" style="background:#777;color:white;padding:10px 22px;border:none;border-radius:6px;cursor:pointer;">No</button>
        </div>
    `;

    // Append modal box to background
    modalBg.appendChild(modalBox);
    document.body.appendChild(modalBg);

    // Add animation via keyframes
    const styleSheet = document.createElement("style");
    styleSheet.innerHTML = `
    @keyframes popupIn {
        0% { transform: scale(0.8); opacity: 0; }
        60% { transform: scale(1.05); opacity: 1; }
        100% { transform: scale(1); opacity: 1; }
    }`;
    document.head.appendChild(styleSheet);

    // Button events
    modalBg.querySelector('#modalYes').addEventListener('click', () => {
        updateStatus(id, status); // call existing function
        modalBg.remove();
    });

    modalBg.querySelector('#modalNo').addEventListener('click', () => modalBg.remove());

    // Click outside to close
    modalBg.addEventListener('click', e => { if(e.target === modalBg) modalBg.remove(); });
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
                modalAlert('Please select a rejection reason.');
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

        // Remove any previous "no-results" row
        table.querySelectorAll('.no-results').forEach(n => n.remove());

        // If no visible rows, show a friendly message row
        const visible = Array.from(rows).filter(r => r.style.display !== 'none');
        if (visible.length === 0) {
            const headerThs = table.querySelectorAll('tr.column-header-row th');
            const colspan = headerThs.length || (table.rows[0] ? table.rows[0].cells.length : 1);
            const tr = document.createElement('tr');
            tr.className = 'no-results';
            const td = document.createElement('td');
            td.colSpan = colspan;
            td.style.textAlign = 'center';
            td.style.padding = '30px 10px';
            td.style.color = '#555';
            td.textContent = 'No Request Found for this filter';
            tr.appendChild(td);
            table.appendChild(tr);
        }
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

    // Run initial filter to reflect current UI state
    filterRows();
}


// ===== Setup all tables with unique filter IDs =====
setupTableFeatures('pendingTable', 'searchInput', 'dateFilter', 'purposeFilterPending', 'exportExcelBtn', 3, 2);
setupTableFeatures('approvedTable', 'searchInputApproved', 'dateFilter', 'purposeFilterApproved', 'exportExcelApproved', 3, 2);
setupTableFeatures('forpickupTable', 'searchInputForPickup', 'dateFilterForPickup', 'purposeFilterForPickup', 'exportExcelForPickup', 4, 2);
setupTableFeatures('completedTable', 'searchInputCompleted', 'dateFilterCompleted', 'purposeFilterCompleted', 'exportExcelCompleted', 4, 2);
setupTableFeatures('rejectedTable', 'searchInputRejected', 'dateFilterRejected', null, 'exportExcelRejected', 3, null, 'rejectedFilter', 4);
// Setup history table filtering
setupTableFeatures('historyTable', 'searchHistory', 'dateFilterHistory', null, null, 0, null);

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
  // Prefer filtered statusData from server; fallback to serverStatusCounts (all-time) if none
  const data = (statusData && Object.keys(statusData).length) ? statusData : ((window.serverStatusCounts && Object.keys(window.serverStatusCounts).length) ? window.serverStatusCounts : {});
  // ensure numeric values
  const safeData = {};
  Object.keys(data).forEach(k => {
    if (!k || String(k).toLowerCase() === 'undefined') return; // skip invalid keys
    safeData[k] = (typeof data[k] === 'number') ? data[k] : (parseInt(data[k]) || 0);
  });
  const total = Object.values(safeData).reduce((a,b) => a+b, 0);
  summaryCardsContainer.innerHTML = `
    <div class="summary-card total"><h3>Total</h3><p>${total}</p></div>
    ${Object.entries(safeData).map(([s,v]) => `
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

  // Bar chart with data labels
  statusChart = new Chart(statusChartCtx, {
    type: 'bar',
    data: {
      labels: Object.keys(data.status),
      datasets: [{ label:'Requests by Status', data:Object.values(data.status),
        backgroundColor:['#ff9800','#4caf50','#2196f3','#673ab7','#f44336'] }]
    },
    options: { 
      responsive:true, 
      plugins:{
        legend:{display:false},
        datalabels: {
          anchor: 'end',
          align: 'top',
          font: { weight: 'bold', size: 12 },
          color: '#000'
        }
      },
      scales: {
        y: { beginAtZero: true, ticks: { stepSize: 1 } }
      }
    },
    plugins: [{
      afterDatasetsDraw(chart) {
        const { ctx, data, chartArea: { left, top, width, height } } = chart;
        ctx.font = 'bold 12px Arial';
        ctx.fillStyle = '#000';
        ctx.textAlign = 'center';
        ctx.textBaseline = 'bottom';
        data.datasets.forEach((dataset, i) => {
          chart.getDatasetMeta(i).data.forEach((point, j) => {
            const value = dataset.data[j];
            const x = point.x;
            const y = point.y;
            ctx.fillText(value, x, y - 5);
          });
        });
      }
    }]
  });

  // Doughnut chart with data labels and percentages
  const purposeTotal = Object.values(data.purpose).reduce((a,b) => a+b, 0);
  purposeChart = new Chart(purposeChartCtx, {
    type:'doughnut',
    data: {
      labels:Object.keys(data.purpose),
      datasets:[{ data:Object.values(data.purpose),
        backgroundColor:['#4caf50','#2196f3','#ff9800','#9c27b0','#f44336','#00bcd4'] }]
    },
    options:{ 
      plugins:{ 
        title:{ display:true, text:'Requests by Purpose' },
        legend: { position: 'bottom' },
        datalabels: {
          formatter: (value) => {
            const percentage = ((value / purposeTotal) * 100).toFixed(1);
            return `${value}\n(${percentage}%)`;
          },
          color: '#fff',
          font: { weight: 'bold', size: 11 }
        }
      },
      responsive: true
    },
    plugins: [{
      afterDatasetsDraw(chart) {
        const { data, chartArea } = chart;
        const meta = chart.getDatasetMeta(0);
        meta.data.forEach((point, i) => {
          const value = data.datasets[0].data[i];
          const percentage = ((value / purposeTotal) * 100).toFixed(1);
          const { x, y } = point.tooltipPosition();
          const ctx = chart.ctx;
          ctx.font = 'bold 11px Arial';
          ctx.fillStyle = '#fff';
          ctx.textAlign = 'center';
          ctx.textBaseline = 'middle';
          ctx.fillText(`${value}`, x, y - 8);
          ctx.fillText(`(${percentage}%)`, x, y + 8);
        });
      }
    }]
  });
}

// Fetch recent requests dynamically
async function updateRecentRequests(time, date) {
  const params = new URLSearchParams({ time, date });
  const res = await fetch(`php/get_recent_requests.php?${params.toString()}`);
  const data = await res.json();

  // Clear and build rows with data-request so clicking shows full details
  recentBody.innerHTML = '';
  data.forEach(r => {
    const tr = document.createElement('tr');
    tr.setAttribute('data-request', JSON.stringify(r));
    tr.className = 'request-row';

    // normalize status: treat missing or literal 'undefined' as Pending
    const normStatus = (r.status && String(r.status).toLowerCase() !== 'undefined') ? r.status : 'Pending';
    const td1 = document.createElement('td'); td1.textContent = r.tracking_no || '';
    const td2 = document.createElement('td'); td2.textContent = (r.firstname || '') + ' ' + (r.lastname || '');
    const td3 = document.createElement('td'); td3.className = 'status ' + (String(normStatus).toLowerCase().replace(/\s/g,'-')); td3.textContent = normStatus || '';
    const td4 = document.createElement('td'); td4.textContent = r.date_submitted || '';

    tr.appendChild(td1); tr.appendChild(td2); tr.appendChild(td3); tr.appendChild(td4);
    // attach click to open details modal with parsed request object
    tr.addEventListener('click', (e) => {
      if (['BUTTON','INPUT','SELECT'].includes(e.target.tagName)) return;
      const obj = JSON.parse(tr.getAttribute('data-request'));
      // normalize status on the object before showing details
      if (!obj.status || String(obj.status).toLowerCase() === 'undefined') obj.status = 'Pending';
      showDetails(obj);
    });

    recentBody.appendChild(tr);
  });
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

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

        fetch('', { method: 'POST', body: formData })
            .then(() => location.reload());
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
            buttons = `<button onclick="updateStatus(${data.id},'Approved')">Approve</button>
                       <button onclick="promptReject(${data.id})">Reject</button>`;
        } else if (data.status === 'Approved') {
            buttons = `<button onclick="setupPickup(${data.id})">Set-up Pick-up Date</button>
                       <button onclick="promptReject(${data.id})">Reject</button>`;
        }

        const makeImg = (file, label) => file 
            ? `<p><b>${label}:</b><br><img src="php/uploads/${file}" alt="${label}" class="preview-img"></p>` 
            : '';

        document.getElementById('details').innerHTML = `
            <h2>${data.firstname} ${data.middleinitial} ${data.lastname}</h2>
            <p><b>Tracking No:</b> ${data.tracking_no}</p>
            <p><b>Address:</b> ${data.address}</p>
            <p><b>Years of Residency:</b> ${data.yearresidency}</p>
            <p><b>Contact:</b> ${data.contact}</p>
            <p><b>Email:</b> ${data.email}</p>
            <p><b>Purpose:</b> ${data.purpose}</p>
            <p><b>Remarks:</b> ${data.remarks || 'None'}</p>
            <p><b>Status:</b> ${data.status}</p>
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


// NEW FEATURES
function setupTableFeatures(tableId, searchId, dateId, purposeId, exportId, dateColumnIndex = 2, purposeColumnIndex = 2) {
  const table = document.getElementById(tableId);
  if (!table) return;

  const rows = table.querySelectorAll('.request-row');

  // Search by name or request number
  if (searchId) {
    const searchInput = document.getElementById(searchId);
    searchInput?.addEventListener('keyup', function() {
      const value = this.value.toLowerCase();
      rows.forEach(row => {
        row.style.display = row.innerText.toLowerCase().includes(value) ? '' : 'none';
      });
    });
  }

  // Filter by date
  if (dateId) {
    const dateInput = document.getElementById(dateId);
    dateInput?.addEventListener('change', function() {
      const filterDate = this.value;
      rows.forEach(row => {
        const dateCell = row.cells[dateColumnIndex]?.innerText.split(' ')[0];
        row.style.display = (!filterDate || dateCell === filterDate) ? '' : 'none';
      });
    });
  }

  // Filter by purpose
  if (purposeId) {
    const purposeInput = document.getElementById(purposeId);
    purposeInput?.addEventListener('change', function() {
      const filter = this.value.toLowerCase();
      rows.forEach(row => {
        const purposeCell = row.cells[purposeColumnIndex]?.innerText.toLowerCase();
        row.style.display = (!filter || purposeCell === filter) ? '' : 'none';
      });
    });
  }

  // Export to Excel
  if (exportId) {
    const exportBtn = document.getElementById(exportId);
    exportBtn?.addEventListener('click', function() {
      const wb = XLSX.utils.table_to_book(table, { sheet: tableId });
      XLSX.writeFile(wb, tableId + ".xlsx");
    });
  }
}

// ======== Setup all tables ========
setupTableFeatures('pendingTable', 'searchInput', 'dateFilter', 'purposeFilter', 'exportExcelBtn', 3, 2);
setupTableFeatures('approvedTable', 'searchInputApproved', 'dateFilterApproved', null, 'exportExcelApproved', 2, 2);
setupTableFeatures('forpickupTable', 'searchInputForPickup', 'dateFilterForPickup', null, 'exportExcelForPickup', 2, 2);
setupTableFeatures('completedTable', 'searchInputCompleted', 'dateFilterCompleted', null, 'exportExcelCompleted', 3, 2);
setupTableFeatures('rejectedTable', 'searchInputRejected', 'dateFilterRejected', null, 'exportExcelRejected', 3, 2);

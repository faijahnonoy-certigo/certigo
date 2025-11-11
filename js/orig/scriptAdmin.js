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
            const reason = prompt('Enter rejection reason:');
            if (!reason) return;
            updateStatus(btn.dataset.id, 'Rejected', null, reason);
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

        // Thumbnail generator
        const makeImg = (file, label) => file 
            ? `<p><b>${label}:</b><br><img src="php/uploads/${file}" alt="${label}" 
                class="preview-img"></p>` 
            : '';

        document.getElementById('details').innerHTML = `
            <h2>${data.firstname} ${data.lastname}</h2>
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

        // Make images enlargeable
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
    // Reject reason prompt
    // ==============================
    window.promptReject = function(id) {
        const reason = prompt('Enter rejection reason:');
        if (!reason) return;
        updateStatus(id, 'Rejected', null, reason);
    };
});


//log out start

document.addEventListener("DOMContentLoaded", function () {
  const logoutBtn = document.getElementById("logoutBtn");
  const logoutModal = document.getElementById("logoutModal");
  const confirmLogout = document.getElementById("confirmLogout");
  const cancelLogout = document.getElementById("cancelLogout");

  if (!logoutBtn || !logoutModal) return;

  // Show modal on button click
  logoutBtn.addEventListener("click", function (e) {
    e.preventDefault();
    logoutModal.style.display = "flex";
  });

  // Hide modal when "Cancel" is clicked
  cancelLogout.addEventListener("click", function () {
    logoutModal.style.display = "none";
  });

  // Redirect to logout.php when confirmed
  confirmLogout.addEventListener("click", function () {
    window.location.href = "php/logout.php";
  });

  // Optional: close modal when clicking outside it
  window.addEventListener("click", function (e) {
    if (e.target === logoutModal) {
      logoutModal.style.display = "none";
    }
  });
});


// logout end
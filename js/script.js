// FOR ACCORDION IN HOMPAGE
document.querySelectorAll('.accordion-header').forEach(header => {
  header.addEventListener('click', () => {
    const item = header.parentElement;
    item.classList.toggle('active');

    // Close others when one is opened
    document.querySelectorAll('.accordion-item').forEach(other => {
      if (other !== item) other.classList.remove('active');
    });
  });
});
// END -----------------


// for hamburger menu---------------------
function toggleMenu() {
  document.querySelector(".hamburger").classList.toggle("active");
  document.querySelector(".nav-drawer").classList.toggle("active");
}
// end -----------


// for reminders page----------------
const faqs = document.querySelectorAll(".faq-item");

faqs.forEach((item) => {
  const question = item.querySelector(".faq-question");

  question.addEventListener("click", () => {
    // Close other open FAQs
    faqs.forEach((faq) => {
      if (faq !== item) faq.classList.remove("active");
    });

    // Toggle the current one
    item.classList.toggle("active");
  });
});

// === FAQ SEARCH ===
// === FAQ SEARCH ===
const faqSearch = document.getElementById("faqSearchInput");

if (faqSearch) {
  faqSearch.addEventListener("keyup", function () {
    let filter = this.value.toLowerCase();
    let items = document.querySelectorAll(".faq-item");

    items.forEach(item => {
      let question = item.querySelector(".faq-question").innerText.toLowerCase();
      let answer = item.querySelector(".faq-answer").innerText.toLowerCase();

      if (question.includes(filter) || answer.includes(filter)) {
        item.style.display = "block";
      } else {
        item.style.display = "none";
      }
    });
  });
}



// end of for reminders page-----------


    //for track status oage-----------------

    // ===============================
    // Track Status Page Script
    // ===============================

    // Status mapping for display and visuals
   const statusMap = {
    Pending: { step: 1, label: 'Pending', color: '#e67e22' },
    Approved: { step: 1, label: 'Approved', color: '#f39c12' }, // still step 1 until pickup scheduled
    "For Pick-up": { step: 3, label: 'For Pick-up', color: '#2980b9' },
    Claimed: { step: 3, label: 'For Pick-up', color: '#2980b9' },
    Completed: { step: 4, label: 'Completed', color: '#27ae60' },
    Rejected: { step: 4, label: 'Rejected', color: '#c0392b' }
};


    // Show error message
    function showError(message) {
        const errorDiv = document.getElementById('errorMessage');
        errorDiv.textContent = message;
        errorDiv.style.display = 'block';
        document.getElementById('resultsSection').style.display = 'none';
    }

    // Hide error message
    function hideError() {
        document.getElementById('errorMessage').style.display = 'none';
    }

    // Validate control number format (e.g. REQ-20251024-8)
    function validateControlNumber(controlNumber) {
        if (!controlNumber || controlNumber.trim() === '') {
            return { valid: false, message: 'Control number is required!' };
        }
        const normalized = controlNumber.trim();
        const reqPattern = /^REQ-\d{8,}-\d+$/i;
        const numeric10 = /^\d{10}$/;
        if (!reqPattern.test(normalized) && !numeric10.test(normalized)) {
            return { valid: false, message: 'Invalid control number. Use REQ-YYYYMMDD-<id> or a 10-digit number.' };
        }
        return { valid: true };
    }

    // Fetch data from backend PHP (real MySQL integration)
    function fetchRequestData(controlNumber) {
        return fetch(`php/fetch_status.php?tracking_no=${encodeURIComponent(controlNumber)}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    return { success: true, data: data.data };
                } else {
                    return { success: false, message: data.message };
                }
            })
            .catch(() => ({ success: false, message: 'Server error. Please try again later.' }));
    }

    // Update visual status bar
    function updateStatusBar(currentStatus) {
        for (let i = 1; i <= 4; i++) {
            const step = document.getElementById(`step${i}`);
            step.classList.remove('active', 'completed');
        }

        const stepNum = statusMap[currentStatus]?.step || 1;
        for (let i = 1; i < stepNum; i++) {
            document.getElementById(`step${i}`).classList.add('completed');
        }
        if (stepNum <= 4) {
            document.getElementById(`step${stepNum}`).classList.add('active');
        }

        document.getElementById('currentStatus').textContent =
            `Current Status: ${statusMap[currentStatus]?.label || 'Unknown'}`;
    }

    // Render basic timeline (example: submitted > approved > completed)
   function renderTimeline(history) {
    const timelineDiv = document.getElementById('timeline');
    timelineDiv.innerHTML = '';

    if (!history || history.length === 0) {
        timelineDiv.innerHTML = '<p>No timeline available.</p>';
        return;
    }

    history.forEach(item => {
        const timelineItem = document.createElement('div');
        timelineItem.className = 'timeline-item';
        timelineItem.innerHTML = `
            <div class="timeline-icon">${item.step}</div>
            <div class="timeline-content">
                <div class="timeline-date">${item.timestamp}</div>
                <p class="timeline-text">${item.event}</p>
            </div>
        `;
        timelineDiv.appendChild(timelineItem);
    });
}


    // Handle submit button
    function trackRequest() {
        const controlNumber = document.getElementById('controlNumberInput').value.trim();
        hideError();

        const validation = validateControlNumber(controlNumber);
        if (!validation.valid) {
            showError(validation.message);
            return;
        }

        const submitBtn = document.getElementById('submitBtn');
        submitBtn.disabled = true;
        submitBtn.textContent = 'Tracking...';

        fetchRequestData(controlNumber)
            .then(response => {
                if (response.success) {
                    const data = response.data;
                    document.getElementById('resultsSection').style.display = 'block';
                    document.getElementById('residentName').textContent =
                        `Resident: ${data.firstname} ${data.lastname}`;
                    // Show user's preferred pickup date (if any)
                    const prefEl = document.getElementById('preferredDateInfo');
                    if (data.preferred_pickup_date) {
                        prefEl.textContent = `Preferred pick-up date: ${data.preferred_pickup_date}`;
                    } else {
                        prefEl.textContent = '';
                    }
                    updateStatusBar(data.status);
                    renderTimeline(data.history || []);
                } else {
                    showError(response.message);
                }
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.textContent = 'Track';
            });
    }

    // Trigger tracking on Enter key while focused in input
    const controlInput = document.getElementById('controlNumberInput');
    if (controlInput) {
        controlInput.addEventListener('keydown', function (e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                trackRequest();
            }
        });
    }



    // end of for track status page


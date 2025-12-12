// for hamburger menu---------------------
function toggleMenu() {
  document.querySelector(".hamburger").classList.toggle("active");
  document.querySelector(".nav-drawer").classList.toggle("active");
}

// for faqs accordion--------------
document.querySelectorAll('.quick-link-header').forEach(header => {
  header.addEventListener('click', () => {
    const item = header.parentElement;
    item.classList.toggle('active');
  });
});


// for login page ----------------------------
const togglePassword = document.getElementById('togglePassword');
const passwordInput = document.getElementById('password');

togglePassword.addEventListener('click', () => {
  const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
  passwordInput.setAttribute('type', type);
  togglePassword.textContent = type === 'password' ? '👁️' : '🙈';
});

//FOR TRACK STATUS PAGE -----------
// JavaScript for Track Status Page Functionality
        // This simulates database integration using mock data.
        // In a real application, replace fetchRequestData() with an actual API call (e.g., fetch('/api/track/:controlNumber')).
        // Assumes control number is a 10-digit numeric string.
        // Status progression: 'pending', 'inprogress', 'for_pickup', 'completed'

        // Mock Database: Simulated request data (in real app, fetch from backend database like MySQL via PHP/Node.js)
        const mockDatabase = [
            {
                controlNumber: '1234567890',
                residentName: 'Juan Dela Cruz',
                status: 'inprogress', // Current status
                history: [
                    { event: 'Request Submitted', timestamp: '2023-10-01 09:00 AM' },
                    { event: 'Under Review', timestamp: '2023-10-02 02:30 PM' }
                ]
            },
            {
                controlNumber: '0987654321',
                residentName: 'Maria Santos',
                status: 'for_pickup',
                history: [
                    { event: 'Request Submitted', timestamp: '2023-10-03 10:15 AM' },
                    { event: 'Under Review', timestamp: '2023-10-04 11:00 AM' },
                    { event: 'Approved for Pick Up', timestamp: '2023-10-05 03:45 PM' }
                ]
            },
            {
                controlNumber: '1111111111',
                residentName: 'Pedro Reyes',
                status: 'completed',
                history: [
                    { event: 'Request Submitted', timestamp: '2023-10-06 08:30 AM' },
                    { event: 'Under Review', timestamp: '2023-10-07 01:20 PM' },
                    { event: 'Approved for Pick Up', timestamp: '2023-10-08 04:00 PM' },
                    { event: 'Document Collected', timestamp: '2023-10-09 11:15 AM' }
                ]
            }
            // Add more mock entries as needed
        ];

        // Status mapping for display and visual updates
        const statusMap = {
            pending: { step: 1, label: 'Pending', color: '#e67e22' },
            inprogress: { step: 2, label: 'In Progress', color: '#f39c12' },
            for_pickup: { step: 3, label: 'For Pick Up', color: '#3498db' },
            completed: { step: 4, label: 'Completed', color: '#27ae60' }
        };

        // Function to show error message
        function showError(message) {
            const errorDiv = document.getElementById('errorMessage');
            errorDiv.textContent = message;
            errorDiv.style.display = 'block';
            document.getElementById('resultsSection').style.display = 'none'; // Hide results on error
        }

        // Function to hide error message
        function hideError() {
            document.getElementById('errorMessage').style.display = 'none';
        }

        // Validation function for control number
        function validateControlNumber(controlNumber) {
            if (!controlNumber || controlNumber.trim() === '') {
                return { valid: false, message: 'Control number is required.' };
            }
            if (!/^\d{10}$/.test(controlNumber)) {
                return { valid: false, message: 'Invalid control number. It must be a 10-digit number.' };
            }
            return { valid: true };
        }

        // Simulate fetching data from database (real-time fetch)
        // In production: Use fetch API to connect to backend (e.g., PHP script querying MySQL)
        // Handles errors gracefully (e.g., network errors, no data)
        function fetchRequestData(controlNumber) {
            // Simulate API delay for real-time feel
            return new Promise((resolve) => {
                setTimeout(() => {
                    const request = mockDatabase.find(req => req.controlNumber === controlNumber);
                    if (request) {
                        resolve({ success: true, data: request });
                    } else {
                        resolve({ success: false, message: 'No request found with this control number.' });
                    }
                }, 500); // 500ms delay to simulate network
            });
        }

        // Function to update visual status bar based on current status
        function updateStatusBar(currentStatus) {
            // Reset all steps
            for (let i = 1; i <= 4; i++) {
                const step = document.getElementById(`step${i}`);
                step.classList.remove('active', 'completed');
            }

            // Mark completed steps (up to current)
            const stepNum = statusMap[currentStatus]?.step || 1;
            for (let i = 1; i < stepNum; i++) {
                document.getElementById(`step${i}`).classList.add('completed');
            }

            // Mark current step as active
            if (stepNum <= 4) {
                document.getElementById(`step${stepNum}`).classList.add('active');
            }

            // Update current status text
            document.getElementById('currentStatus').textContent = `Current Status: ${statusMap[currentStatus]?.label || 'Unknown'}`;
        }

        // Function to render timeline (detailed request history)
        function renderTimeline(history) {
            const timelineDiv = document.getElementById('timeline');
            timelineDiv.innerHTML = ''; // Clear previous

            history.forEach((item, index) => {
                const timelineItem = document.createElement('div');
                timelineItem.className = 'timeline-item';

                const icon = document.createElement('div');
                icon.className = 'timeline-icon';
                icon.textContent = index + 1; // Simple numbering

                const content = document.createElement('div');
                content.className = 'timeline-content';

                const date = document.createElement('div');
                date.className = 'timeline-date';
                date.textContent = item.timestamp;

                const text = document.createElement('p');
                text.className = 'timeline-text';
                text.textContent = item.event;

                content.appendChild(date);
                content.appendChild(text);
                timelineItem.appendChild(icon);
                timelineItem.appendChild(content);
                timelineDiv.appendChild(timelineItem);
            });
        }

        // Main function to track request on submit
        function trackRequest() {
            const controlNumber = document.getElementById('controlNumberInput').value.trim();
            hideError();

            // Client-side validation
            const validation = validateControlNumber(controlNumber);
            if (!validation.valid) {
                showError(validation.message);
                return;
            }

            // Disable submit button during fetch to prevent multiple submissions
            const submitBtn = document.getElementById('submitBtn');
            submitBtn.disabled = true;
            submitBtn.textContent = 'Tracking...';

            // Fetch data from "database" (simulated)
            fetchRequestData(controlNumber)
                .then(response => {
                    if (response.success) {
                        // Show results
                        document.getElementById('resultsSection').style.display = 'block';
                        document.getElementById('residentName').textContent = `Resident: ${response.data.residentName}`;
                        updateStatusBar(response.data.status);
                        renderTimeline(response.data.history);
                    } else {
                        showError(response.message);
                    }
                })
                .catch(error => {
                    // Graceful error handling (e.g., network error)
                    console.error('Error fetching data:', error);
                    showError('An error occurred while fetching the request status. Please try again.');
                })
                .finally(() => {
                    // Re-enable button
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Submit';
                });
        }

        // Additional event listener for Enter key on input
        document.getElementById('controlNumberInput').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                trackRequest();
            }
        });

        // Acceptance Criteria Notes (for documentation):
        // - Data is fetched in real-time from database: Simulated with Promise and timeout; replace with actual fetch().
        // - Errors are handled gracefully: Try-catch in fetch, client/server-side validations.
        // - Invalid numbers show clear error message: Handled in validateControlNumber and fetch.
        // - Timeline shows request submission and updates: Rendered dynamically with dates/times.
        // - Status bar updates from database values: Based on fetched status.
        // - Input required/validation: Checked before fetch.
        // For full integration: Connect to backend (e.g., Node.js/Express with MySQL) via API endpoint.
 


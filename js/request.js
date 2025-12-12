// --- Configuration ---
const MAX_FILE_SIZE_BYTES = 2 * 1024 * 1024; // 2MB

// --- Toggle Mobile Menu ---
function toggleMenu() {
    document.querySelector(".hamburger")?.classList.toggle("active");
    document.querySelector(".nav-drawer")?.classList.toggle("active");
}

// --- Capitalize Inputs ---
document.addEventListener("DOMContentLoaded", () => {
    const capitalizeInputs = document.querySelectorAll('input.capitalize, textarea.capitalize');
    capitalizeInputs.forEach(input => {
        input.addEventListener('input', () => {
            const words = input.value.split(' ').map(word => {
                return word.length
                    ? word.charAt(0).toUpperCase() + word.slice(1).toLowerCase()
                    : '';
            });
            input.value = words.join(' ');
        });
    });
});

// --- Navbar Scroll Effect ---
document.addEventListener("DOMContentLoaded", () => {
    const navbar = document.getElementById("navbar");
    if (!navbar) return;

    const scrollThreshold = 50;

    const handleScroll = () => {
        if (window.scrollY > scrollThreshold) {
            navbar.classList.add("opacity-95", "shadow-2xl");
        } else {
            navbar.classList.remove("opacity-95", "shadow-2xl");
        }
    };

    window.addEventListener("scroll", handleScroll);
    handleScroll();
});

// --- Update Phase 1 fields based on request type ---
function updatePhase1Fields() {
    const requestFor = document.querySelector('input[name="requestFor"]:checked')?.value;
    const dependentSection = document.getElementById('dependentSection');
    
    if (requestFor === 'self') {
        // For personal request, hide the dependent section
        if (dependentSection) dependentSection.classList.add('hidden');
    } else if (requestFor === 'child') {
        // For child, show dependent section but with different label
        if (dependentSection) dependentSection.classList.remove('hidden');
    } else if (requestFor === 'others') {
        // For others, show dependent section
        if (dependentSection) dependentSection.classList.remove('hidden');
    }
}

// --- Update upload requirements based on request type ---
function updateUploadRequirements(requestFor) {
    const additionalPhotoSection = document.getElementById('additionalPhotoSection');
    
    if (requestFor === 'others') {
        // For requesting on behalf of someone else, require additional photo
        if (additionalPhotoSection) {
            additionalPhotoSection.classList.remove('hidden');
            const photoInput = additionalPhotoSection.querySelector('input[type="file"]');
            if (photoInput) photoInput.setAttribute('required', 'required');
        }
    } else {
        // For self or child, no additional photo needed
        if (additionalPhotoSection) {
            additionalPhotoSection.classList.add('hidden');
            const photoInput = additionalPhotoSection.querySelector('input[type="file"]');
            if (photoInput) photoInput.removeAttribute('required');
        }
    }
}

// --- File Upload Handling ---
function handleFileUpload(input, labelId) {
    const labelSpan = document.getElementById(labelId);
    if (!labelSpan) return;

    const uploadBox = labelSpan.closest(".file-upload-box");
    const file = input.files[0];

    uploadBox?.classList.remove("border-error-red");
    document.getElementById("file-error-message")?.classList.add("hidden");

    if (file) {
        // Validate file type: only JPG and PNG are allowed
        const allowedTypes = ['image/jpeg', 'image/png'];
        const allowedExtensions = ['.jpg', '.jpeg', '.png'];
        const fileExtension = file.name.substring(file.name.lastIndexOf('.')).toLowerCase();
        const isValidType = allowedTypes.includes(file.type) || allowedExtensions.includes(fileExtension);

        if (!isValidType) {
            labelSpan.textContent = "Invalid file type! Only JPG and PNG files are allowed";
            labelSpan.classList.remove("uploaded");
            labelSpan.style.color = "#d32f2f";
            uploadBox?.classList.add("border-error-red");
            input.value = ""; // Clear the input
            showPopupMessage("Only JPG and PNG files are allowed. PDF and other file types are not accepted.", "error");
            return;
        }

        if (file.size > MAX_FILE_SIZE_BYTES) {
            labelSpan.textContent = "File too large! (Max 2MB)";
            labelSpan.classList.remove("uploaded");
            labelSpan.style.color = "#d32f2f";
            uploadBox?.classList.add("border-error-red");
        } else {
            labelSpan.textContent = file.name;
            labelSpan.classList.add("uploaded");
            labelSpan.style.color = ""; // Reset color to default
        }
    } else {
        labelSpan.textContent = "No file chosen";
        labelSpan.classList.remove("uploaded");
        labelSpan.style.color = ""; // Reset color to default
    }
}

// --- N/A Checkbox ---
function toggleNA(inputId, checkbox) {
    const input = document.getElementById(inputId);
    if (checkbox.checked) {
        input.value = "N/A";
        input.disabled = true;
    } else {
        input.disabled = false;
        input.value = "";
    }
}

// --- Modern Modal Error ---
function showPopupMessage(msg, type = "success") {
    if (typeof Swal !== "undefined") {
        Swal.fire({
            icon: type === "error" ? "error" : "success",
            title: type === "error" ? "Error" : "Success",
            text: msg,
            confirmButtonColor: type === "error" ? "#d33" : "#3085d6"
        });
    } else {
        alert(msg);
    }
}

// --- DOMContentLoaded Main ---
document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("indigencyForm");
    const confirmModal = document.getElementById("requestConfirmModal");
    const confirmBtn = document.getElementById("confirmRequestBtn");
    const cancelBtn = document.getElementById("cancelRequestBtn");

    const dobInput = document.getElementById("dateofbirth");
    const ageInput = document.getElementsByName("age")[0];
    const residencyInput = document.getElementsByName("yearresidency")[0];
    // pickupSchedule required is set in HTML; no removal here

    if (ageInput) ageInput.readOnly = true;

    // Auto-calculate age on DOB change
    if (dobInput && ageInput) {
        dobInput.addEventListener("change", function () {
            calculateAge();
        });
    }

    function calculateAge() {
        if (!dobInput.value) return 0;
        const dob = new Date(dobInput.value);
        const today = new Date();
        let age = today.getFullYear() - dob.getFullYear();
        const monthDiff = today.getMonth() - dob.getMonth();
        const dayDiff = today.getDate() - dob.getDate();
        if (monthDiff < 0 || (monthDiff === 0 && dayDiff < 0)) age--;
        ageInput.value = age > 0 ? age : 0;
        return age;
    }

    // --- Step Navigation ---
    window.goToPhase0 = function () {
        document.getElementById("phase0").classList.remove("hidden");
        document.getElementById("phase1").classList.add("hidden");
        document.getElementById("phase2").classList.add("hidden");
        document.getElementById("step1")?.classList.remove("complete");
        document.getElementById("step1")?.classList.add("active");
        document.getElementById("step2")?.classList.remove("active", "complete");
        document.getElementById("step3")?.classList.remove("active", "complete");
        document.querySelector(".form-card")?.scrollIntoView({ behavior: "smooth", block: "start" });
    };

    window.goToPhase1 = function () {
        const requestFor = document.querySelector('input[name="requestFor"]:checked');
        if (!requestFor) {
            showPopupMessage("Please select who you are requesting the certification for.", "error");
            return;
        }

        document.getElementById("phase0").classList.add("hidden");
        document.getElementById("phase1").classList.remove("hidden");
        document.getElementById("phase2").classList.add("hidden");
        document.getElementById("step1")?.classList.remove("active");
        document.getElementById("step1")?.classList.add("complete");
        document.getElementById("step2")?.classList.add("active");
        document.getElementById("step3")?.classList.remove("active", "complete");
        document.querySelector(".form-card")?.scrollIntoView({ behavior: "smooth", block: "start" });
    };

    window.backToPhase0 = function () {
        document.getElementById("phase0").classList.remove("hidden");
        document.getElementById("phase1").classList.add("hidden");
        document.getElementById("phase2").classList.add("hidden");
        document.getElementById("step1")?.classList.remove("complete");
        document.getElementById("step1")?.classList.add("active");
        document.getElementById("step2")?.classList.remove("active", "complete");
        document.getElementById("step3")?.classList.remove("active", "complete");
        document.querySelector(".form-card")?.scrollIntoView({ behavior: "smooth", block: "start" });
    };

    window.backToPhase1 = function () {
        document.getElementById("phase0").classList.add("hidden");
        document.getElementById("phase1").classList.remove("hidden");
        document.getElementById("phase2").classList.add("hidden");
        document.getElementById("step2")?.classList.remove("complete");
        document.getElementById("step2")?.classList.add("active");
        document.getElementById("step3")?.classList.remove("active", "complete");
        document.querySelector(".form-card")?.scrollIntoView({ behavior: "smooth", block: "start" });
    };

    window.goToPhase2 = function (event) {
        if (event) event.preventDefault();

        const age = calculateAge();
        if (age < 18) {
            showPopupMessage("You must be at least 18 years old to request this certificate.", "error");
            return;
        }

        const residency = parseInt(residencyInput.value);
        if (!isNaN(residency) && residency > age) {
            showPopupMessage(`Years of residency cannot exceed your age (${age} years).`, "error");
            residencyInput.classList.add("border-error-red");
            return;
        } else {
            residencyInput.classList.remove("border-error-red");
        }

        const phase1 = document.getElementById("phase1");
        const inputs = phase1.querySelectorAll("[required]");
        let allValid = true;

        inputs.forEach(input => {
            if (!input.checkValidity() || !input.value.trim()) {
                input.classList.add("border-error-red");
                input.reportValidity();
                allValid = false;
            } else {
                input.classList.remove("border-error-red");
            }
        });

        if (allValid) {
            const requestFor = document.querySelector('input[name="requestFor"]:checked').value;
            updateUploadRequirements(requestFor);

            // pickupSchedule is required via HTML attribute

            document.getElementById("phase0").classList.add("hidden");
            document.getElementById("phase1").classList.add("hidden");
            document.getElementById("phase2").classList.remove("hidden");
            document.getElementById("step2")?.classList.remove("active");
            document.getElementById("step2")?.classList.add("complete");
            document.getElementById("step3")?.classList.add("active");
            document.querySelector(".form-card")?.scrollIntoView({ behavior: "smooth", block: "start" });
        }
    };

    window.handleFileUpload = handleFileUpload;

    // --- Open confirmation modal on submit ---
    form.addEventListener("submit", function (e) {
        e.preventDefault();

        const age = calculateAge();
        if (age < 18) {
            showPopupMessage("You must be at least 18 years old to request this certificate.", "error");
            return;
        }

        const residency = parseInt(residencyInput.value);
        if (!isNaN(residency) && residency > age) {
            showPopupMessage(`Years of residency cannot exceed your age (${age} years).`, "error");
            residencyInput.classList.add("border-error-red");
            return;
        } else {
            residencyInput.classList.remove("border-error-red");
        }

        // Validate native required fields (including pickup date)
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        // Ensure grecaptcha is loaded and user completed it
        let captchaResponse = '';
        if (typeof grecaptcha !== 'undefined') {
            captchaResponse = grecaptcha.getResponse();
            if (!captchaResponse) {
                showPopupMessage("Please complete the CAPTCHA first.", "error");
                // Try to render the captcha if it hasn't been rendered
                const captchaContainer = document.getElementById('recaptchaContainer');
                if (captchaContainer && !captchaContainer.getAttribute('data-widget-id')) {
                    grecaptcha.render(captchaContainer, {
                        sitekey: '6Lf2CQIsAAAAANP349fIoVTMUBx3uUVeeoMaQD3L'
                    });
                }
                return;
            }
        } else {
            showPopupMessage("CAPTCHA not loaded. Please refresh the page and try again.", "error");
            return;
        }

        confirmModal.classList.remove("hidden");
    });

    // --- Cancel modal ---
    cancelBtn.addEventListener("click", () => {
        confirmModal.classList.add("hidden");
    });

    // --- Confirm and submit ---
    confirmBtn.addEventListener("click", async function () {
        confirmModal.classList.add("hidden");

        // Validate pickup date is not in the past (if user selected one)
        const pickupInput = document.getElementById('pickupSchedule');
        if (pickupInput && pickupInput.value) {
            const selected = new Date(pickupInput.value);
            const today = new Date();
            today.setHours(0,0,0,0);
            if (selected < today) {
                showPopupMessage('Pick-up date cannot be in the past.', 'error');
                return;
            }
        }

        const spinner = document.getElementById("loadingSpinner");
        spinner.classList.remove("hidden");

        const formData = new FormData(form);
        // append reCAPTCHA token so server can verify
        if (typeof grecaptcha !== 'undefined') {
            formData.append('g-recaptcha-response', grecaptcha.getResponse());
        }

        try {
            const response = await fetch(form.action, { method: "POST", body: formData });
            let result;
            try {
                result = await response.json();
            } catch (parseErr) {
                const text = await response.text();
                spinner.classList.add("hidden");
                console.error('Non-JSON response from server:', text);
                showPopupMessage('Server error: ' + (text.substring(0,200) || 'Unexpected response'), 'error');
                if (typeof grecaptcha !== 'undefined') grecaptcha.reset();
                return;
            }
            spinner.classList.add("hidden");

            if (result.status === "success") {
                const summaryDiv = document.getElementById("userDetailsSummary");
                summaryDiv.innerHTML = `
                    <div style="display:flex; justify-content:center; align-items:center; gap:10px; margin-top:10px;">
                        <input type="text" id="trackingNumberInput" 
                            value="${result.tracking_no}" readonly
                            style="width:260px; padding:12px; text-align:center; border-radius:8px; border:1px solid #ccc; font-size:18px;">
                        <button id="copyTrackNumberBtn"
                            style="padding:12px 15px; background:#007bff; color:white; border:none; border-radius:8px; cursor:pointer;">
                            Copy
                        </button>
                    </div>
                `;

                const successModal = document.getElementById("requestSuccessModal");
                successModal.classList.remove("hidden");

                document.getElementById("copyTrackNumberBtn").onclick = () => {
                    const input = document.getElementById("trackingNumberInput");
                    navigator.clipboard.writeText(input.value)
                        .then(() => Swal.fire({ icon: 'success', title: 'Copied!', text: 'Tracking Number copied.', timer: 1500, showConfirmButton: false }))
                        .catch(() => alert("📋 Tracking Number copied."));
                };

                form.reset();
                grecaptcha.reset();

                document.getElementById("closeSuccessModalBtn").onclick = () => {
                    successModal.classList.add("hidden");
                    window.location.href = "request.html";
                };
            } else {
                showPopupMessage(result.message || "Something went wrong.", "error");
                grecaptcha.reset();
            }
        } catch (error) {
            spinner.classList.add("hidden");
            console.error("Error:", error);
            showPopupMessage("A network or server error occurred. Please try again.", "error");
            grecaptcha.reset();
        }
    });

});

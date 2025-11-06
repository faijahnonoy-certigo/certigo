// --- Configuration ---
const MAX_FILE_SIZE_BYTES = 2 * 1024 * 1024; // 2MB

// --- Toggle Mobile Menu ---
function toggleMenu() {
  document.querySelector(".hamburger")?.classList.toggle("active");
  document.querySelector(".nav-drawer")?.classList.toggle("active");
}

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

// --- Go to Phase 2 (Validation) ---
function goToPhase2(event) {
  if (event) event.preventDefault();

  const phase1 = document.getElementById("phase1");
  const phase2 = document.getElementById("phase2");
  const errorMsg = document.getElementById("error-message");
  if (!phase1 || !phase2) return;

  const inputs = Array.from(phase1.querySelectorAll(".form-input[required]"));
  inputs.forEach((input) => input.classList.remove("border-error-red"));
  errorMsg?.classList.add("hidden");

  const allValid = inputs.every((input) => {
    const valid = input.value.trim() !== "";
    if (!valid) input.classList.add("border-error-red");
    return valid;
  });

  if (allValid) {
    phase1.classList.add("hidden");
    phase2.classList.remove("hidden");
    document.getElementById("step1")?.classList.replace("active", "complete");
    document.getElementById("step2")?.classList.add("active");
    document.querySelector(".form-card")?.scrollIntoView({ behavior: "smooth", block: "start" });
  } else {
    errorMsg?.classList.remove("hidden");
  }
}

// --- Go back to Phase 1 ---
function goToPhase1() {
  const phase1 = document.getElementById("phase1");
  const phase2 = document.getElementById("phase2");
  if (!phase1 || !phase2) return;

  phase2.classList.add("hidden");
  phase1.classList.remove("hidden");
  document.getElementById("step1")?.classList.add("active");
  document.getElementById("step1")?.classList.remove("complete");
  document.getElementById("step2")?.classList.remove("active");
  document.getElementById("file-error-message")?.classList.add("hidden");
  document.querySelector(".form-card")?.scrollIntoView({ behavior: "smooth", block: "start" });
}

// --- Validate Before Submit ---
function validateBeforeSubmit(event) {
  const form = document.getElementById("indigencyForm");
  const errorMsg = document.getElementById("error-message");
  const fileErrorMsg = document.getElementById("file-error-message");
  let valid = true;

  // Hide old error messages
  if (errorMsg) errorMsg.classList.add("hidden");
  if (fileErrorMsg) fileErrorMsg.classList.add("hidden");

  // Text field validation
  const requiredIds = ["firstName", "lastName", "address", "residency", "contact", "email", "purpose"];
  requiredIds.forEach((id) => {
    const input = document.getElementById(id);
    if (input && input.value.trim() === "") {
      input.classList.add("border-error-red");
      valid = false;
    } else {
      input?.classList.remove("border-error-red");
    }
  });

  // File validation
  const fileInputs = ["uploadValidId", "uploadCedula", "uploadPhotoId"];
  fileInputs.forEach((id) => {
    const input = document.getElementById(id);
    const file = input?.files[0];
    const box = input?.closest(".file-upload-box");
    if (!file) {
      valid = false;
      box?.classList.add("border-error-red");
    } else {
      box?.classList.remove("border-error-red");
    }
  });

  if (!valid) {
    event.preventDefault();
    if (errorMsg) errorMsg.classList.remove("hidden");
    form.scrollIntoView({ behavior: "smooth", block: "start" });
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
    if (file.size > MAX_FILE_SIZE_BYTES) {
      labelSpan.textContent = `File too large! (Max 2MB)`;
      labelSpan.classList.remove("uploaded");
      uploadBox?.classList.add("border-error-red");
    } else {
      labelSpan.textContent = file.name;
      labelSpan.classList.add("uploaded");
    }
  } else {
    labelSpan.textContent = "No file chosen";
    labelSpan.classList.remove("uploaded");
  }
}

// --- Simple Message Popup ---
function showPopupMessage(msg, type = "success") {
  if (type === "error") {
    window.alert("❌ " + msg);
  } else {
    window.alert("✅ " + msg);
  }
}

document.addEventListener("DOMContentLoaded", function () {
  const form = document.getElementById("indigencyForm");
  const confirmModal = document.getElementById("requestConfirmModal");
  const confirmBtn = document.getElementById("confirmRequestBtn");
  const cancelBtn = document.getElementById("cancelRequestBtn");

  // === Step Navigation ===
  window.goToPhase2 = function (event) {
    event.preventDefault();
    document.getElementById("phase1").classList.add("hidden");
    document.getElementById("phase2").classList.remove("hidden");
  };

  window.goToPhase1 = function () {
    document.getElementById("phase2").classList.add("hidden");
    document.getElementById("phase1").classList.remove("hidden");
  };

  window.handleFileUpload = function (input, labelId) {
    const fileName = input.files[0] ? input.files[0].name : "No file chosen";
    document.getElementById(labelId).textContent = fileName;
  };

  // === Open confirmation modal on submit ===
  form.addEventListener("submit", function (e) {
    e.preventDefault();

    const captchaResponse = grecaptcha.getResponse();
    if (!captchaResponse) {
      alert("⚠️ Please complete the CAPTCHA first.");
      return;
    }

    confirmModal.classList.remove("hidden");
  });

  // === Cancel modal ===
  cancelBtn.addEventListener("click", () => {
    confirmModal.classList.add("hidden");
  });

  // === Confirm and submit form ===
   // === Confirm and submit form ===
confirmBtn.addEventListener("click", async function () {
  confirmModal.classList.add("hidden");

  const formData = new FormData(form);

  try {
    const response = await fetch(form.action, {
      method: "POST",
      body: formData,
    });

    const result = await response.json();

    if (result.status === "success") {
      // ✅ Create modal content
      const summaryDiv = document.getElementById("userDetailsSummary");
      const firstname = formData.get("firstname");
      const lastname = formData.get("lastname");
      const address = formData.get("address");
      const contact = formData.get("contact");
      const email = formData.get("email");
      const purpose = formData.get("purpose");

      summaryDiv.innerHTML = `
        <p><strong>Tracking No:</strong> ${result.tracking_no}</p>
        <p><strong>Name:</strong> ${firstname} ${lastname}</p>
        <p><strong>Address:</strong> ${address}</p>
        <p><strong>Contact:</strong> ${contact}</p>
        <p><strong>Email:</strong> ${email}</p>
        <p><strong>Purpose:</strong> ${purpose}</p>
      `;

      // ✅ Show success modal
      const successModal = document.getElementById("requestSuccessModal");
      successModal.classList.remove("hidden");

      // Reset form and captcha
      form.reset();
      grecaptcha.reset();

      // Close success modal
      document.getElementById("closeSuccessModalBtn").onclick = () => {
        successModal.classList.add("hidden");
        window.location.href = "request.html"; // reload clean form
      };
    } else {
      alert(`❌ ${result.message || "Something went wrong. Try again."}`);
      grecaptcha.reset();
    }
  } catch (error) {
    console.error("Error:", error);
    alert("❌ A network or server error occurred. Please try again.");
    grecaptcha.reset();
  }
});
});

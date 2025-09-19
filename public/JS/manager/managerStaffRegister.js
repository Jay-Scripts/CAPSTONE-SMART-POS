const staffNameInput = document.getElementById("staffName");
const staffNameFeedback = document.getElementById("staffNameFeedback");
const spinner = document.getElementById("loadingSpinner");
const form = document.getElementById("staffRegistrationForm");
const submitBtn = document.getElementById("submitBtn");

// =====================
// Staff Name Validation
// =====================
const validateStaffName = () => {
  const name = staffNameInput.value.trim();
  if (!name) {
    staffNameFeedback.textContent = "Name is required.";
    staffNameFeedback.className = "text-red-500 text-sm mt-1";
    return false;
  } else if (name.length < 4) {
    staffNameFeedback.textContent = "Name must be at least 4 characters.";
    staffNameFeedback.className = "text-red-500 text-sm mt-1";
    return false;
  } else {
    staffNameFeedback.textContent = "Good!";
    staffNameFeedback.className = "text-green-600 text-sm mt-1";
    return true;
  }
};

// =================
// Limit input letters then single space only
//==========================
staffNameInput.addEventListener("input", () => {
  let value = staffNameInput.value;
  value = value.replace(/[^A-Za-z ]/g, ""); // letters and space only
  value = value.replace(/\s{2,}/g, " "); // multiple  single space
  value = value.replace(/^\s+/g, ""); // remove leading spaces
  staffNameInput.value = value;

  validateStaffName();
});

// ======================
// Submit with spinner
// ======================
form.addEventListener("submit", () => {
  if (!validateStaffName()) {
    return; // mag stop if invalid
  }

  
  
});

document
  .getElementById("staffStatusForm")
  .addEventListener("submit", async function (e) {
    e.preventDefault(); // prevent page reload

    const staffID = document.getElementById("staffID").value.trim();
    const staffStatus = document.querySelector(
      'input[name="staffStatus"]:checked'
    );

    // ✅ Front-end validation
    if (!staffID) {
      Swal.fire({
        icon: "warning",
        title: "Staff ID Required",
        text: "Please enter the Staff ID to continue.",
      });
      return;
    }

    if (!staffStatus) {
      Swal.fire({
        icon: "warning",
        title: "Select Status",
        text: "Please choose a status for the staff.",
      });
      return;
    }

    try {
      const formData = new FormData();
      formData.append("staffID", staffID);
      formData.append("staffStatus", staffStatus.value);

      const response = await fetch(
        "../../app/includes/managerModule/managerUpdateStaffStatus.php",
        {
          method: "POST",
          body: formData,
        }
      );

      // ✅ Safely parse JSON
      let result;
      try {
        result = await response.json();
      } catch {
        Swal.fire({
          icon: "error",
          title: "Invalid Response",
          text: "Server did not return valid JSON.",
        });
        return;
      }

      // ✅ SweetAlert based on server response
      if (result.status === "success") {
        Swal.fire({
          icon: "success",
          title: "Updated!",
          text: result.message,
        });
        this.reset(); // optional: reset form
      } else {
        Swal.fire({
          icon: "error",
          title: "Oops!",
          text: result.message,
        });
      }
    } catch (err) {
      Swal.fire({
        icon: "error",
        title: "Network Error",
        text: "Could not connect to server.",
      });
      console.error(err);
    }
  });

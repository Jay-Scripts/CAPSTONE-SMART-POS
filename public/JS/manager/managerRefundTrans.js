document
  .getElementById("refundForm")
  .addEventListener("submit", async function (e) {
    e.preventDefault();

    const form = e.target;
    const formData = new FormData(form);

    // ✅ Basic validation
    if (!formData.get("REG_TRANSACTION_ID") || !formData.get("reason")) {
      Swal.fire({
        icon: "warning",
        title: "Missing Fields",
        text: "Please fill in all required fields before submitting.",
      });
      return;
    }

    try {
      const response = await fetch(
        "../../app/includes/managerModule/managerRefundTrans.php",
        {
          method: "POST",
          body: formData,
        }
      );
      const result = await response.json();

      if (result.status === "success") {
        Swal.fire({
          icon: "success",
          title: "Refund Logged",
          text: result.message,
          timer: 2000,
          showConfirmButton: false,
        });
        form.reset(); // reset form after success
      } else {
        Swal.fire({
          icon: "error",
          title: "Error",
          text: result.message,
        });
      }
    } catch (err) {
      Swal.fire({
        icon: "error",
        title: "Network Error",
        text: err.message,
      });
    }
  });

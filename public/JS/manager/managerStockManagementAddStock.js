document.addEventListener("DOMContentLoaded", () => {
  const modal = document.getElementById("inventoryModal");
  const openModalBtn = document.getElementById("openModalBtn");
  const closeModalBtn = document.getElementById("closeModalBtn");
  const inventoryForm = document.getElementById("inventoryForm");

  openModalBtn.addEventListener("click", () =>
    modal.classList.remove("hidden")
  );
  closeModalBtn.addEventListener("click", () => modal.classList.add("hidden"));

  inventoryForm.addEventListener("submit", async (e) => {
    e.preventDefault();

    const item_name = document.getElementById("item_name").value.trim();
    const inv_category = document.getElementById("inv_category").value;
    const prod_category =
      document.getElementById("prod_category").value || null;
    const product = document.getElementById("product").value || null;
    const quantity = document.getElementById("quantity").value.trim();
    const unit = document.getElementById("unit").value;
    const date_made = document.getElementById("date_made").value;
    const date_expiry = document.getElementById("date_expiry").value;

    // Validation
    if (
      !item_name ||
      !inv_category ||
      !quantity ||
      !unit ||
      !date_made ||
      !date_expiry
    ) {
      Swal.fire({
        icon: "warning",
        title: "Incomplete Data",
        text: "Please fill in all required fields.",
        confirmButtonColor: "#facc15",
      });
      return;
    }

    const formData = new FormData();
    formData.append("item_name", item_name);
    formData.append("inv_category", inv_category);
    formData.append("category_id", prod_category);
    formData.append("product_id", product);
    formData.append("quantity", quantity);
    formData.append("unit", unit);
    formData.append("date_made", date_made);
    formData.append("date_expiry", date_expiry);

    try {
      const res = await fetch(
        "../../app/includes/managerModule/managerStockManagementAddStocks.php",
        {
          method: "POST",
          body: formData,
        }
      );
      const data = await res.json();

      if (data.status === "success") {
        Swal.fire({
          icon: "success",
          title: "Success!",
          text: data.message,
          confirmButtonColor: "#16a34a",
        }).then(() => {
          inventoryForm.reset();
          modal.classList.add("hidden");
        });
      } else {
        Swal.fire({
          icon: "warning",
          title: "Oops...",
          text: data.message,
          confirmButtonColor: "#facc15",
        });
      }
    } catch (err) {
      Swal.fire({
        icon: "error",
        title: "Server Error",
        text: "Something went wrong.",
        confirmButtonColor: "#dc2626",
      });
      console.error(err);
    }
  });
});

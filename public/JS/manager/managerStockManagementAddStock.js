const modal = document.getElementById("inventoryModal");
const openModalBtn = document.getElementById("openModalBtn");
const closeModalBtn = document.getElementById("closeModalBtn");
const inventoryForm = document.getElementById("inventoryForm");

// 🟢 Open and Close modal
openModalBtn.addEventListener("click", () => modal.classList.remove("hidden"));
closeModalBtn.addEventListener("click", () => modal.classList.add("hidden"));

// 🟡 Handle form submission
inventoryForm.addEventListener("submit", async (e) => {
  e.preventDefault();

  const item_name = document.getElementById("item_name").value.trim();
  const category = document.getElementById("category").value;
  const quantity = document.getElementById("quantity").value.trim();
  const unit = document.getElementById("unit").value;

  // 🔍 Front-end validation
  if (!item_name || !category || !quantity || !unit) {
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
  formData.append("category", category);
  formData.append("quantity", quantity);
  formData.append("unit", unit);

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
        // 🔄 Optional: refresh inventory table
        // loadInventoryItems();
      });
    } else {
      Swal.fire({
        icon: "warning",
        title: "Oops...",
        text: data.message,
        confirmButtonColor: "#facc15",
      });
    }
  } catch (error) {
    Swal.fire({
      icon: "error",
      title: "Server Error",
      text: "Something went wrong while saving.",
      confirmButtonColor: "#dc2626",
    });
    console.error("Fetch error:", error);
  }
});

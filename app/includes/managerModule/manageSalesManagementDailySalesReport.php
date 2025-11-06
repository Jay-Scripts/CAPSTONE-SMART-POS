  <!--  Daily Staff Sales Report -->
  <div class="glass-card bg-[var(--glass-bg)] rounded-xl shadow p-6 hover:shadow-2xl transition">
      <h3 class="text-lg font-semibold mb-3 text-[var(--text-color)] flex items-center gap-2">
          <i class="fa-solid fa-user-tie text-blue-500"></i> Daily Staff Sales Report
      </h3>

      <form id="staffSalesForm" class="flex flex-col gap-3 items-center">
          <!-- Cashier ID -->
          <label for="cashierId" class="text-[var(--text-color)]">Cashier ID:</label>
          <input type="number" id="cashierId" placeholder="Scan or Enter Cashier ID"
              class="border border-[var(--border-color)] bg-transparent rounded-lg px-3 py-2 text-[var(--text-color)] w-full">

          <!-- Date (default today) -->
          <label for="reportDate" class="text-[var(--text-color)]">Report Date:</label>
          <input type="date" id="reportDate"
              class="border border-[var(--border-color)] bg-transparent rounded-lg px-3 py-2 text-[var(--text-color)] w-full">

          <!-- Total handedd cash -->
          <input type="number" id="handedCash" placeholder="₱ Total Sales"
              class="border border-[var(--border-color)] bg-transparent rounded-lg px-3 py-2 text-[var(--text-color)] w-full font-semibold text-center">



          <!-- Generate Button -->
          <button type="button" onclick="generateStaffReport()"
              class="bg-blue-500 hover:bg-blue-600 text-white rounded-lg px-4 py-2 mt-2 w-full">
              Generate Report
          </button>
      </form>
  </div>
  <script>
      document.getElementById("reportDate").valueAsDate = new Date();

      async function generateStaffReport() {
          const id = document.getElementById("cashierId").value.trim();
          const date = document.getElementById("reportDate").value;
          const handedCash = document.getElementById("handedCash").value.trim();

          if (!id) return Swal.fire({
              icon: "error",
              title: "Missing ID",
              text: "Please scan or enter a Cashier ID."
          });
          if (!date) return Swal.fire({
              icon: "error",
              title: "Missing Date",
              text: "Please select a report date."
          });
          if (!handedCash) return Swal.fire({
              icon: "error",
              title: "Missing Cash",
              text: "Please input the total handed cash."
          });

          const start_date = `${date} 00:00:00`;
          const end_date = `${date} 23:59:59`;

          try {
              const res = await fetch(`../../app/includes/managerModule/getStaffSales.php?cashier_id=${id}&start_date=${start_date}&end_date=${end_date}&handed_cash=${handedCash}`);
              const data = await res.json();

              if (data.success) {
                  Swal.fire({
                      icon: "success",
                      title: "Report Generated!",
                      showConfirmButton: true
                  }).then(() => {
                      window.open(`../../app/includes/managerModule/cashierSalesReport.php?cashier_id=${id}&start_date=${start_date}&end_date=${end_date}&handed_cash=${handedCash}`, "_blank");
                  });
              } else {
                  Swal.fire({
                      icon: "error",
                      title: "No Data",
                      text: data.message || "No sales found."
                  });
              }
          } catch (err) {
              Swal.fire({
                  icon: "error",
                  title: "Network Error",
                  text: err.message
              });
          }
      }
  </script>
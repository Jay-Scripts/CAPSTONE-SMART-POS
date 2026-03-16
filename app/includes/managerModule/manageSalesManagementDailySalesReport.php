<!-- Daily Staff Sales Report -->
<div class="glass-card bg-[var(--glass-bg)] rounded-xl shadow p-6 hover:shadow-2xl transition flex flex-col gap-4">

    <!-- Header -->
    <div class="flex items-center gap-2 pb-3 border-b border-[var(--border-color)]">
        <div class="w-9 h-9 rounded-lg bg-blue-500/15 flex items-center justify-center shrink-0">
            <i class="fa-solid fa-user-tie text-blue-500 text-sm"></i>
        </div>
        <div>
            <h3 class="text-sm font-bold text-[var(--text-color)] leading-tight">Daily Staff Sales Report</h3>
            <p class="text-xs opacity-50 text-[var(--text-color)]">Generate per-cashier sales summary</p>
        </div>
    </div>

    <!-- Form Fields -->
    <form id="staffSalesForm" class="flex flex-col gap-3">

        <!-- Cashier ID -->
        <div class="flex flex-col gap-1">
            <label class="text-xs font-semibold uppercase tracking-wide opacity-60 text-[var(--text-color)]">
                Cashier ID
            </label>
            <div class="relative">
                <i class="fa-solid fa-id-badge absolute left-3 top-1/2 -translate-y-1/2 text-[var(--text-color)] opacity-40 text-xs"></i>
                <input
                    type="number"
                    id="cashierId"
                    placeholder="Scan or enter cashier ID"
                    class="w-full pl-8 pr-3 py-2.5 text-sm rounded-xl border border-[var(--border-color)]
                           bg-[var(--background-color)] text-[var(--text-color)]
                           focus:outline-none focus:ring-2 focus:ring-blue-400 transition" />
            </div>
        </div>

        <!-- Report Date -->
        <div class="flex flex-col gap-1">
            <label class="text-xs font-semibold uppercase tracking-wide opacity-60 text-[var(--text-color)]">
                Report Date
            </label>
            <div class="relative">
                <i class="fa-solid fa-calendar-day absolute left-3 top-1/2 -translate-y-1/2 text-[var(--text-color)] opacity-40 text-xs"></i>
                <input
                    type="date"
                    id="reportDate"
                    class="w-full pl-8 pr-3 py-2.5 text-sm rounded-xl border border-[var(--border-color)]
                           bg-[var(--background-color)] text-[var(--text-color)]
                           focus:outline-none focus:ring-2 focus:ring-blue-400 transition" />
            </div>
        </div>

        <!-- Total Handed Cash -->
        <div class="flex flex-col gap-1">
            <label class="text-xs font-semibold uppercase tracking-wide opacity-60 text-[var(--text-color)]">
                Total Handed Cash
            </label>
            <div class="relative">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-[var(--text-color)] opacity-40 text-sm font-bold">₱</span>
                <input
                    type="number"
                    id="handedCash"
                    placeholder="0.00"
                    min="0"
                    step="0.01"
                    class="w-full pl-7 pr-3 py-2.5 text-sm rounded-xl border border-[var(--border-color)]
                           bg-[var(--background-color)] text-[var(--text-color)]
                           focus:outline-none focus:ring-2 focus:ring-blue-400 transition" />
            </div>
        </div>

        <!-- Generate Button -->
        <button
            type="button"
            onclick="generateStaffReport()"
            class="mt-1 w-full py-3 rounded-xl bg-blue-500 hover:bg-blue-600 active:scale-[0.98]
                   text-white text-sm font-bold tracking-wide flex items-center justify-center gap-2
                   transition-all duration-200 shadow-md">
            <i class="fa-solid fa-file-chart-column"></i>
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
                    })
                    .then(() => {
                        window.open(
                            `../../app/includes/managerModule/cashierSalesReport.php?cashier_id=${id}&start_date=${start_date}&end_date=${end_date}&handed_cash=${handedCash}`,
                            "_blank"
                        );
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
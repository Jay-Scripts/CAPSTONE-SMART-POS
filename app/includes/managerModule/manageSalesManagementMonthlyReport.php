<!-- Monthly Summary Report -->
<div class="glass-card bg-[var(--glass-bg)] rounded-xl shadow p-6 hover:shadow-2xl transition flex flex-col gap-4">

    <!-- Header -->
    <div class="flex items-center gap-2 pb-3 border-b border-[var(--border-color)]">
        <div class="w-9 h-9 rounded-lg bg-green-500/15 flex items-center justify-center shrink-0">
            <i class="fa-solid fa-calendar-days text-green-500 text-sm"></i>
        </div>
        <div>
            <h3 class="text-sm font-bold text-[var(--text-color)] leading-tight">Monthly Summary</h3>
            <p class="text-xs opacity-50 text-[var(--text-color)]">Generate monthly sales overview</p>
        </div>
    </div>

    <!-- Month Picker -->
    <div class="flex flex-col gap-1">
        <label class="text-xs font-semibold uppercase tracking-wide opacity-60 text-[var(--text-color)]">
            Select Month
        </label>
        <div class="relative">
            <i class="fa-solid fa-calendar absolute left-3 top-1/2 -translate-y-1/2 text-[var(--text-color)] opacity-40 text-xs"></i>
            <input
                type="month"
                id="monthlyDate"
                class="w-full pl-8 pr-3 py-2.5 text-sm rounded-xl border border-[var(--border-color)]
                       bg-[var(--background-color)] text-[var(--text-color)]
                       focus:outline-none focus:ring-2 focus:ring-green-400 transition" />
        </div>
    </div>

    <!-- Generate Button -->
    <button
        id="generateMonthly"
        class="w-full py-3 rounded-xl bg-green-500 hover:bg-green-600 active:scale-[0.98]
               text-white text-sm font-bold tracking-wide flex items-center justify-center gap-2
               transition-all duration-200 shadow-md">
        <i class="fa-solid fa-file-chart-column"></i>
        Generate Report
    </button>

</div>

<script>
    document.getElementById('generateMonthly').addEventListener('click', () => {
        const monthInput = document.getElementById('monthlyDate').value.trim();

        if (!monthInput) {
            Swal.fire({
                icon: 'warning',
                title: 'No Month Selected',
                text: 'Please select a month before generating the report.',
            });
            return;
        }

        const [year, month] = monthInput.split('-').map(Number);
        const selectedMonthDate = new Date(year, month - 1, 1);
        const now = new Date();
        now.setDate(1);
        now.setHours(0, 0, 0, 0);

        if (selectedMonthDate > now) {
            Swal.fire({
                icon: 'error',
                title: 'Future Month',
                text: 'You cannot generate a report for a future month.',
            });
            return;
        }

        window.open(
            `../../app/includes/managerModule/monthlySalesReport.php?month=${encodeURIComponent(monthInput)}`,
            '_blank'
        );
    });
</script>
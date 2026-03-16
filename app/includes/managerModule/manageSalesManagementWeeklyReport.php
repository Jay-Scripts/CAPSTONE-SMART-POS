<!-- Weekly Summary Report -->
<div class="glass-card bg-[var(--glass-bg)] rounded-xl shadow p-6 hover:shadow-2xl transition flex flex-col gap-4">

    <!-- Header -->
    <div class="flex items-center gap-2 pb-3 border-b border-[var(--border-color)]">
        <div class="w-9 h-9 rounded-lg bg-green-500/15 flex items-center justify-center shrink-0">
            <i class="fa-solid fa-calendar-week text-green-500 text-sm"></i>
        </div>
        <div>
            <h3 class="text-sm font-bold text-[var(--text-color)] leading-tight">Weekly Summary</h3>
            <p class="text-xs opacity-50 text-[var(--text-color)]">Generate weekly sales overview</p>
        </div>
    </div>

    <!-- Week Picker -->
    <div class="flex flex-col gap-1">
        <label class="text-xs font-semibold uppercase tracking-wide opacity-60 text-[var(--text-color)]">
            Select Week
        </label>
        <div class="relative">
            <i class="fa-solid fa-calendar-days absolute left-3 top-1/2 -translate-y-1/2 text-[var(--text-color)] opacity-40 text-xs"></i>
            <input
                type="week"
                id="weeklyDate"
                class="w-full pl-8 pr-3 py-2.5 text-sm rounded-xl border border-[var(--border-color)]
                       bg-[var(--background-color)] text-[var(--text-color)]
                       focus:outline-none focus:ring-2 focus:ring-green-400 transition" />
        </div>
    </div>

    <!-- Generate Button -->
    <button
        id="generateWeekly"
        class="w-full py-3 rounded-xl bg-green-500 hover:bg-green-600 active:scale-[0.98]
               text-white text-sm font-bold tracking-wide flex items-center justify-center gap-2
               transition-all duration-200 shadow-md">
        <i class="fa-solid fa-file-chart-column"></i>
        Generate Report
    </button>

</div>

<script>
    document.getElementById('generateWeekly').addEventListener('click', () => {
        const weekInput = document.getElementById('weeklyDate').value.trim();

        if (!weekInput) {
            Swal.fire({
                icon: 'warning',
                title: 'No Week Selected',
                text: 'Please select a week before generating the report.',
            });
            return;
        }

        const [year, week] = weekInput.split('-W').map(Number);

        function getISOWeekStartDate(y, w) {
            const simple = new Date(y, 0, 1 + (w - 1) * 7);
            const dow = simple.getDay();
            const ISOWeekStart = simple;
            if (dow <= 4) {
                ISOWeekStart.setDate(simple.getDate() - simple.getDay() + 1);
            } else {
                ISOWeekStart.setDate(simple.getDate() + 8 - simple.getDay());
            }
            return ISOWeekStart;
        }

        function getISOWeekNumber(date) {
            const tmp = new Date(date.getTime());
            tmp.setHours(0, 0, 0, 0);
            tmp.setDate(tmp.getDate() + 4 - (tmp.getDay() || 7));
            const yearStart = new Date(tmp.getFullYear(), 0, 1);
            return Math.ceil((((tmp - yearStart) / 86400000) + 1) / 7);
        }

        const startDate = getISOWeekStartDate(year, week);
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        const thisWeekStart = getISOWeekStartDate(today.getFullYear(), getISOWeekNumber(today));

        if (startDate > thisWeekStart) {
            Swal.fire({
                icon: 'error',
                title: 'Future Week',
                text: 'You cannot generate a report for a future week.',
            });
            return;
        }

        window.open(
            `../../app/includes/managerModule/weeklySalesReport.php?week=${encodeURIComponent(weekInput)}`,
            "_blank"
        );
    });
</script>
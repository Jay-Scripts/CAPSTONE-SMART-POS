<!-- Weekly Summary Report -->
<div class="glass-card bg-[var(--glass-bg)] rounded-xl p-5 shadow hover:shadow-2xl transition flex flex-col justify-between">
    <h3 class="text-lg font-semibold text-[var(--text-color)] flex items-center gap-2">
        <i class="fa-solid fa-calendar-week text-green-500"></i> Weekly Summary
    </h3>

    <input type="week" id="weeklyDate"
        class="mt-3 border border-[var(--border-color)] bg-transparent rounded-lg px-3 py-2 text-[var(--text-color)] focus:ring-2 focus:ring-green-500 outline-none">

    <button id="generateWeekly"
        class="mt-4 bg-green-500 hover:bg-green-600 text-white rounded-lg px-4 py-2 transition-all duration-200">
        Generate
    </button>
</div>

<script>
    document.getElementById('generateWeekly').addEventListener('click', () => {
        const weekInput = document.getElementById('weeklyDate').value.trim();

        // Validation: empty input
        if (!weekInput) {
            Swal.fire({
                icon: 'warning',
                title: 'Oops!',
                text: 'Please select a week before generating the report.',
            });
            return;
        }

        // Parse year and week
        const [year, week] = weekInput.split('-W').map(Number);

        // Function to get the start date of an ISO week
        function getISOWeekStartDate(y, w) {
            const simple = new Date(y, 0, 1 + (w - 1) * 7);
            const dow = simple.getDay();
            const ISOWeekStart = simple;
            if (dow <= 4) {
                ISOWeekStart.setDate(simple.getDate() - simple.getDay() + 1); // Monday
            } else {
                ISOWeekStart.setDate(simple.getDate() + 8 - simple.getDay()); // next Monday
            }
            return ISOWeekStart;
        }

        const startDate = getISOWeekStartDate(year, week);
        const endDate = new Date(startDate);
        endDate.setDate(startDate.getDate() + 6);

        const today = new Date();
        today.setHours(0, 0, 0, 0);

        // Only block weeks that **start after the current week**
        const thisWeekStart = getISOWeekStartDate(today.getFullYear(), getISOWeekNumber(today));

        function getISOWeekNumber(date) {
            const tmp = new Date(date.getTime());
            tmp.setHours(0, 0, 0, 0);
            tmp.setDate(tmp.getDate() + 4 - (tmp.getDay() || 7));
            const yearStart = new Date(tmp.getFullYear(), 0, 1);
            const weekNo = Math.ceil((((tmp - yearStart) / 86400000) + 1) / 7);
            return weekNo;
        }

        if (startDate > thisWeekStart) {
            Swal.fire({
                icon: 'error',
                title: 'Invalid Week!',
                text: 'You cannot generate a report for a future week.',
            });
            return;
        }

        // Sanitize input
        const sanitizedWeek = encodeURIComponent(weekInput);

        // Open PHP report
        const url = `../../app/includes/managerModule/weeklySalesReport.php?week=${sanitizedWeek}`;
        window.open(url, "_blank");
    });
</script>
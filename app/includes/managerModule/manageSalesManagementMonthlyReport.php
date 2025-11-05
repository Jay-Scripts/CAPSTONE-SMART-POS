<!-- Monthly Summary Report -->
<div class="bg-[var(--calc-bg-btn)] rounded-xl p-5 shadow hover:shadow-md transition flex flex-col justify-between">
    <h3 class="text-lg font-semibold text-[var(--text-color)] flex items-center gap-2">
        <i class="fa-solid fa-calendar-days text-green-500"></i> Monthly Summary
    </h3>
    <input type="month" id="monthlyDate" class="mt-3 border border-[var(--border-color)] bg-transparent rounded-lg px-3 py-2 text-[var(--text-color)] focus:ring-2 focus:ring-green-500 outline-none">
    <button id="generateMonthly"
        class="mt-4 bg-green-500 hover:bg-green-600 text-white rounded-lg px-4 py-2 transition-all duration-200">
        Generate
    </button>
</div>


<script>
    document.getElementById('generateMonthly').addEventListener('click', () => {
        const monthInput = document.getElementById('monthlyDate').value.trim();

        // Validate empty
        if (!monthInput) {
            Swal.fire({
                icon: 'warning',
                title: 'Oops!',
                text: 'Please select a month before generating the report.',
            });
            return;
        }

        // Sanitize input
        const sanitizedMonth = encodeURIComponent(monthInput);

        // Check if selected month is in the future
        const [year, month] = monthInput.split('-').map(Number);
        const selectedMonthDate = new Date(year, month - 1, 1); // first day of selected month
        const now = new Date();
        now.setDate(1); // set to first day of current month for comparison
        now.setHours(0, 0, 0, 0);

        if (selectedMonthDate > now) {
            Swal.fire({
                icon: 'error',
                title: 'Invalid Month!',
                text: 'You cannot generate a report for a future month.',
            });
            return;
        }

        // Open PHP report
        const url = `../../app/includes/managerModule/monthlySalesReport.php?month=${sanitizedMonth}`;
        window.open(url, '_blank');
    });
</script>
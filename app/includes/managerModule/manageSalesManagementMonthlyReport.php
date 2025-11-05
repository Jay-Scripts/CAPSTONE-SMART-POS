<!-- Monthly Summary Report -->
<div class="bg-[var(--calc-bg-btn)] rounded-xl p-5 shadow hover:shadow-md transition flex flex-col justify-between">
    <h3 class="text-lg font-semibold text-[var(--text-color)] flex items-center gap-2">
        <i class="fa-solid fa-calendar-days text-green-500"></i> Monthly Summary
    </h3>
    <input type="month" id="monthlyDate" class="mt-3 border border-[var(--border-color)] bg-transparent rounded-lg px-3 py-2 text-[var(--text-color)] focus:ring-2 focus:ring-green-500 outline-none">
    <button onclick="generateReport('monthly')"
        class="mt-4 bg-green-500 hover:bg-green-600 text-white rounded-lg px-4 py-2 transition-all duration-200">
        Generate
    </button>
</div>
<script>
    function generateReport(type) {
        if (type === 'monthly') {
            const month = document.getElementById('monthlyDate').value;
            if (!month) {
                alert("Please select a month.");
                return;
            }
            // Open the monthly report in a new window/tab
            window.open(`../../app/includes/managerModule/monthlySalesReport.php?month=${month}`, '_blank');
        }
    }
</script>
<!-- Weekly Summary Report -->
<div class="bg-[var(--calc-bg-btn)] rounded-xl p-5 shadow hover:shadow-md transition flex flex-col justify-between">
    <h3 class="text-lg font-semibold text-[var(--text-color)] flex items-center gap-2">
        <i class="fa-solid fa-calendar-week text-green-500"></i> Weekly Summary
    </h3>

    <input type="week" id="weeklyDate"
        class="mt-3 border border-[var(--border-color)] bg-transparent rounded-lg px-3 py-2 text-[var(--text-color)] focus:ring-2 focus:ring-green-500 outline-none">

    <button onclick="generateReport('weekly')"
        class="mt-4 bg-green-500 hover:bg-green-600 text-white rounded-lg px-4 py-2 transition-all duration-200">
        Generate
    </button>
</div>
<script>
    async function generateReport(type) {
        if (type !== 'weekly') return;

        const weekInput = document.getElementById('weeklyDate').value;
        if (!weekInput) return Swal.fire({
            icon: "error",
            title: "Missing Week",
            text: "Please select a week to generate the report."
        });

        // Parse week input: format = YYYY-Www
        const [year, week] = weekInput.split('-W').map(Number);

        // Calculate start and end dates of the week
        const simpleDate = new Date(year, 0, 1 + (week - 1) * 7);
        const dayOfWeek = simpleDate.getDay();
        const startDate = new Date(simpleDate.setDate(simpleDate.getDate() - dayOfWeek + 1));
        const endDate = new Date(startDate);
        endDate.setDate(endDate.getDate() + 6);

        const start_date = startDate.toISOString().split('T')[0] + " 00:00:00";
        const end_date = endDate.toISOString().split('T')[0] + " 23:59:59";

        // Open weekly report page
        window.open(`../../app/includes/managerModule/weeklySalesReport.php?start_date=${start_date}&end_date=${end_date}`, "_blank");
    }
</script>
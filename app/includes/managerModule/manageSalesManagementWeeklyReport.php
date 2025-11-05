<!-- Weekly Summary Report -->
<div class="bg-[var(--calc-bg-btn)] rounded-xl p-5 shadow hover:shadow-md transition flex flex-col justify-between">
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
        const weekInput = document.getElementById('weeklyDate').value;
        if (!weekInput) {
            alert("Please select a week.");
            return;
        }

        // Send week input as query parameter to PHP
        const url = `../../app/includes/managerModule/weeklySalesReport.php?week=${weekInput}`;
        window.open(url, "_blank");
    });
</script>
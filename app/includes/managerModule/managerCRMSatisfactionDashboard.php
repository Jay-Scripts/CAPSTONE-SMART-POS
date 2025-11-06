 <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6 mb-6">
     <div class="bg-amber-50 p-4 rounded-lg shadow">
         <h3 class="text-lg md:text-xl font-semibold mb-2">Average Ratings Today</h3>
         <canvas id="avgRatingsChart"></canvas>
     </div>
     <div class="bg-amber-50 p-4 rounded-lg shadow">
         <h3 class="text-lg md:text-xl font-semibold mb-2">Today's Overall Satisfaction</h3>
         <p class="text-gray-700 mb-2">Total Feedback Submitted: <strong id="totalFeedback">0</strong></p>
         <canvas id="overallSatisfactionChart"></canvas>
     </div>
 </div>

 <div class="bg-white shadow rounded-lg p-4">
     <h3 class="text-lg md:text-xl font-semibold mb-4">Feedback Details</h3>
     <input type="text" id="searchInput" placeholder="Search reviews..."
         class="mb-4 w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-400 focus:outline-none">

     <div class="overflow-x-auto">
         <table class="w-full table-auto border-collapse" id="feedbackTable">
             <thead class="bg-amber-200">
                 <tr>
                     <th class="p-2 border">Transaction ID</th>
                     <th class="p-2 border">Staff Attitude</th>
                     <th class="p-2 border">Product Accuracy</th>
                     <th class="p-2 border">Cleanliness</th>
                     <th class="p-2 border">Speed</th>
                     <th class="p-2 border">Overall</th>
                     <th class="p-2 border">Average</th>
                     <th class="p-2 border">Feedback</th>
                     <th class="p-2 border">Date</th>
                 </tr>
             </thead>
             <tbody></tbody>
         </table>
     </div>

     <div class="mt-4 flex justify-between items-center flex-col md:flex-row gap-2 md:gap-0">
         <div id="paginationInfo" class="text-sm text-gray-600"></div>
         <div class="flex gap-1">
             <button id="prevPage" class="px-3 py-1 border rounded-l-lg bg-gray-100 hover:bg-gray-200">Prev</button>
             <button id="nextPage" class="px-3 py-1 border rounded-r-lg bg-gray-100 hover:bg-gray-200">Next</button>
         </div>
     </div>
 </div>
 <script>
     let avgChart, overallChart;
     let table = document.getElementById('feedbackTable').getElementsByTagName('tbody')[0];
     let rows = [];
     const rowsPerPage = 5;
     let currentPage = 1;
     let filteredRows = [];

     // Render table function
     function renderTable() {
         table.innerHTML = '';
         let start = (currentPage - 1) * rowsPerPage;
         let end = start + rowsPerPage;
         filteredRows.slice(start, end).forEach(r => table.appendChild(r));
         document.getElementById('paginationInfo').textContent =
             `Showing ${start+1}-${Math.min(end, filteredRows.length)} of ${filteredRows.length} entries`;
     }

     // Fetch data from server
     async function fetchFeedback() {
         const res = await fetch('../../app/includes/managerModule/managerCRMGetFeedback.php');
         const data = await res.json();

         document.getElementById('totalFeedback').textContent = data.total;

         // Update table
         rows = data.feedbacks.map(f => {
             const tr = document.createElement('tr');
             const avg = ((f.staff_attitude + f.product_accuracy + f.cleanliness + f.speed_of_service + f.overall_satisfaction) / 5).toFixed(2);
             tr.innerHTML = `
            <td class="p-2 border">${f.reg_transaction_id}</td>
            <td class="p-2 border text-center">${f.staff_attitude}</td>
            <td class="p-2 border text-center">${f.product_accuracy}</td>
            <td class="p-2 border text-center">${f.cleanliness}</td>
            <td class="p-2 border text-center">${f.speed_of_service}</td>
            <td class="p-2 border text-center">${f.overall_satisfaction}</td>
            <td class="p-2 border text-center font-semibold">${avg}</td>
            <td class="p-2 border">${f.feedback_text}</td>
            <td class="p-2 border">${f.date_submitted}</td>
        `;
             return tr;
         });
         filteredRows = [...rows];
         currentPage = 1;
         renderTable();

         // Update charts
         const avgData = Object.values(data.avg);
         if (!avgChart) {
             avgChart = new Chart(document.getElementById('avgRatingsChart').getContext('2d'), {
                 type: 'bar',
                 data: {
                     labels: ['Staff', 'Product', 'Cleanliness', 'Speed', 'Overall'],
                     datasets: [{
                         label: 'Average Rating',
                         data: avgData,
                         backgroundColor: '#f59e0b'
                     }]
                 },
                 options: {
                     responsive: true,
                     scales: {
                         y: {
                             min: 0,
                             max: 5
                         }
                     }
                 }
             });
         } else {
             avgChart.data.datasets[0].data = avgData;
             avgChart.update();
         }

         if (!overallChart) {
             overallChart = new Chart(document.getElementById('overallSatisfactionChart').getContext('2d'), {
                 type: 'doughnut',
                 data: {
                     labels: ['Achieved', 'Remaining'],
                     datasets: [{
                         data: [data.overall_percent, 100 - data.overall_percent],
                         backgroundColor: ['#f59e0b', '#e5e7eb']
                     }]
                 },
                 options: {
                     responsive: true,
                     plugins: {
                         title: {
                             display: true,
                             text: "Today's Overall Satisfaction (%)"
                         }
                     }
                 }
             });
         } else {
             overallChart.data.datasets[0].data = [data.overall_percent, 100 - data.overall_percent];
             overallChart.update();
         }
     }

     // Search input
     document.getElementById('searchInput').addEventListener('input', e => {
         const val = e.target.value.toLowerCase();
         filteredRows = rows.filter(r => r.textContent.toLowerCase().includes(val));
         currentPage = 1;
         renderTable();
     });
     document.getElementById('prevPage').addEventListener('click', () => {
         if (currentPage > 1) {
             currentPage--;
             renderTable();
         }
     });
     document.getElementById('nextPage').addEventListener('click', () => {
         if (currentPage * rowsPerPage < filteredRows.length) {
             currentPage++;
             renderTable();
         }
     });

     // Fetch every second
     fetchFeedback();
     setInterval(fetchFeedback, 1000);
 </script>
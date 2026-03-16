<!-- ── FEEDBACK CHARTS ROW ── -->
<div class="space-y-4">

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">

        <!-- Average Ratings -->
        <article class="glass-card rounded-2xl border border-[var(--container-border)] bg-[var(--glass-bg)] shadow-sm p-5 flex flex-col gap-3">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg bg-amber-500/15 flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4 text-amber-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-[var(--text-color)] leading-tight">Average Ratings Today</h3>
                    <p class="text-xs opacity-50 text-[var(--text-color)]">Per feedback category</p>
                </div>
            </div>
            <canvas id="avgRatingsChart"></canvas>
        </article>

        <!-- Overall Satisfaction -->
        <article class="glass-card rounded-2xl border border-[var(--container-border)] bg-[var(--glass-bg)] shadow-sm p-5 flex flex-col gap-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-green-500/15 flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4 text-green-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10" />
                            <path d="M8 14s1.5 2 4 2 4-2 4-2" />
                            <line x1="9" y1="9" x2="9.01" y2="9" />
                            <line x1="15" y1="9" x2="15.01" y2="9" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-[var(--text-color)] leading-tight">Overall Satisfaction</h3>
                        <p class="text-xs opacity-50 text-[var(--text-color)]">Today's score</p>
                    </div>
                </div>
                <!-- Total feedback badge -->
                <div class="flex items-center gap-1.5 px-3 py-1 rounded-xl border border-[var(--container-border)] bg-[var(--background-color)]">
                    <span class="text-xs opacity-50 text-[var(--text-color)]">Total:</span>
                    <span class="text-sm font-bold text-[var(--text-color)]" id="totalFeedback">0</span>
                </div>
            </div>
            <canvas id="overallSatisfactionChart"></canvas>
        </article>

    </div>

    <!-- ── FEEDBACK TABLE ── -->
    <article class="glass-card rounded-2xl border border-[var(--container-border)] bg-[var(--glass-bg)] shadow-sm p-5 flex flex-col gap-4">

        <!-- Table header -->
        <div class="flex items-center justify-between gap-3 flex-wrap">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg bg-blue-500/15 flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4 text-blue-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                        <polyline points="14 2 14 8 20 8" />
                        <line x1="16" y1="13" x2="8" y2="13" />
                        <line x1="16" y1="17" x2="8" y2="17" />
                        <polyline points="10 9 9 9 8 9" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-[var(--text-color)] leading-tight">Feedback Details</h3>
                    <p class="text-xs opacity-50 text-[var(--text-color)]">All submitted reviews</p>
                </div>
            </div>

            <!-- Search -->
            <div class="relative flex-1 min-w-[200px] max-w-xs">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-3.5 h-3.5 opacity-40 text-[var(--text-color)]"
                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8" />
                    <path d="m21 21-4.35-4.35" />
                </svg>
                <input type="text" id="searchInput" placeholder="Search reviews…"
                    class="w-full pl-8 pr-3 py-2 text-sm rounded-xl border border-[var(--container-border)]
                           bg-[var(--background-color)] text-[var(--text-color)]
                           focus:outline-none focus:ring-2 focus:ring-blue-400 transition" />
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto rounded-xl border border-[var(--container-border)]">
            <table class="w-full text-sm" id="feedbackTable">
                <thead>
                    <tr class="border-b border-[var(--container-border)] bg-[var(--calc-bg-btn)]">
                        <th class="px-3 py-2.5 text-left text-xs font-semibold uppercase tracking-wide opacity-60 text-[var(--text-color)] whitespace-nowrap">Txn ID</th>
                        <th class="px-3 py-2.5 text-center text-xs font-semibold uppercase tracking-wide opacity-60 text-[var(--text-color)] whitespace-nowrap">Staff</th>
                        <th class="px-3 py-2.5 text-center text-xs font-semibold uppercase tracking-wide opacity-60 text-[var(--text-color)] whitespace-nowrap">Product</th>
                        <th class="px-3 py-2.5 text-center text-xs font-semibold uppercase tracking-wide opacity-60 text-[var(--text-color)] whitespace-nowrap">Clean</th>
                        <th class="px-3 py-2.5 text-center text-xs font-semibold uppercase tracking-wide opacity-60 text-[var(--text-color)] whitespace-nowrap">Speed</th>
                        <th class="px-3 py-2.5 text-center text-xs font-semibold uppercase tracking-wide opacity-60 text-[var(--text-color)] whitespace-nowrap">Overall</th>
                        <th class="px-3 py-2.5 text-center text-xs font-semibold uppercase tracking-wide opacity-60 text-[var(--text-color)] whitespace-nowrap">Avg</th>
                        <th class="px-3 py-2.5 text-left text-xs font-semibold uppercase tracking-wide opacity-60 text-[var(--text-color)]">Feedback</th>
                        <th class="px-3 py-2.5 text-left text-xs font-semibold uppercase tracking-wide opacity-60 text-[var(--text-color)] whitespace-nowrap">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[var(--container-border)]"></tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="flex items-center justify-between gap-2 flex-wrap">
            <span id="paginationInfo" class="text-xs opacity-50 text-[var(--text-color)]"></span>
            <div class="flex gap-1.5">
                <button id="prevPage"
                    class="px-3 py-1.5 text-xs font-semibold rounded-xl border border-[var(--container-border)]
                           text-[var(--text-color)] bg-[var(--background-color)]
                           hover:bg-[var(--text-color)] hover:text-[var(--background-color)]
                           active:scale-95 transition-all duration-150">
                    ← Prev
                </button>
                <button id="nextPage"
                    class="px-3 py-1.5 text-xs font-semibold rounded-xl border border-[var(--container-border)]
                           text-[var(--text-color)] bg-[var(--background-color)]
                           hover:bg-[var(--text-color)] hover:text-[var(--background-color)]
                           active:scale-95 transition-all duration-150">
                    Next →
                </button>
            </div>
        </div>

    </article>

</div>

<script>
    let avgChart, overallChart;
    let table = document.getElementById('feedbackTable').getElementsByTagName('tbody')[0];
    let rows = [];
    const rowsPerPage = 5;
    let currentPage = 1;
    let filteredRows = [];

    // Star helper — renders ★ filled/empty based on score
    function starBadge(score) {
        const n = parseInt(score);
        const colors = ['text-red-400', 'text-orange-400', 'text-yellow-400', 'text-lime-500', 'text-green-500'];
        const color = colors[Math.min(n - 1, 4)] || 'text-gray-400';
        return `<span class="font-bold ${color}">${score} ★</span>`;
    }

    function avgBadge(avg) {
        const n = parseFloat(avg);
        let cls = 'bg-red-500/10 text-red-400';
        if (n >= 4.5) cls = 'bg-green-500/10 text-green-500';
        else if (n >= 3.5) cls = 'bg-lime-500/10 text-lime-500';
        else if (n >= 2.5) cls = 'bg-yellow-500/10 text-yellow-500';
        else if (n >= 1.5) cls = 'bg-orange-500/10 text-orange-400';
        return `<span class="inline-block px-2 py-0.5 rounded-lg text-xs font-bold ${cls}">${avg}</span>`;
    }

    function renderTable() {
        table.innerHTML = '';
        const start = (currentPage - 1) * rowsPerPage;
        const end = start + rowsPerPage;
        filteredRows.slice(start, end).forEach(r => table.appendChild(r));
        document.getElementById('paginationInfo').textContent =
            `Showing ${start + 1}–${Math.min(end, filteredRows.length)} of ${filteredRows.length} entries`;
    }

    async function fetchFeedback() {
        const res = await fetch('../../app/includes/managerModule/managerCRMGetFeedback.php');
        const data = await res.json();

        document.getElementById('totalFeedback').textContent = data.total;

        rows = data.feedbacks.map(f => {
            const tr = document.createElement('tr');
            const avg = ((f.staff_attitude + f.product_accuracy + f.cleanliness + f.speed_of_service + f.overall_satisfaction) / 5).toFixed(2);
            const date = f.date_submitted.split(' ')[0];
            const time = f.date_submitted.split(' ')[1]?.slice(0, 5) || '';
            tr.className = 'hover:bg-[var(--calc-bg-btn)] transition-colors duration-100';
            tr.innerHTML = `
                <td class="px-3 py-2.5 text-xs font-mono text-[var(--text-color)] opacity-70">#${f.reg_transaction_id}</td>
                <td class="px-3 py-2.5 text-center">${starBadge(f.staff_attitude)}</td>
                <td class="px-3 py-2.5 text-center">${starBadge(f.product_accuracy)}</td>
                <td class="px-3 py-2.5 text-center">${starBadge(f.cleanliness)}</td>
                <td class="px-3 py-2.5 text-center">${starBadge(f.speed_of_service)}</td>
                <td class="px-3 py-2.5 text-center">${starBadge(f.overall_satisfaction)}</td>
                <td class="px-3 py-2.5 text-center">${avgBadge(avg)}</td>
                <td class="px-3 py-2.5 text-xs text-[var(--text-color)] max-w-[180px] truncate" title="${f.feedback_text || ''}">${f.feedback_text || '<span class="opacity-30">—</span>'}</td>
                <td class="px-3 py-2.5 text-xs text-[var(--text-color)] opacity-60 whitespace-nowrap">${date} ${time}</td>
            `;
            return tr;
        });

        filteredRows = [...rows];
        currentPage = 1;
        renderTable();

        // ── Charts ──
        const avgData = Object.values(data.avg);

        if (!avgChart) {
            avgChart = new Chart(document.getElementById('avgRatingsChart').getContext('2d'), {
                type: 'bar',
                data: {
                    labels: ['Staff', 'Product', 'Cleanliness', 'Speed', 'Overall'],
                    datasets: [{
                        label: 'Average Rating',
                        data: avgData,
                        backgroundColor: [
                            'rgba(59,130,246,0.7)',
                            'rgba(16,185,129,0.7)',
                            'rgba(245,158,11,0.7)',
                            'rgba(168,85,247,0.7)',
                            'rgba(239,68,68,0.7)',
                        ],
                        borderRadius: 8,
                        borderSkipped: false,
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            min: 0,
                            max: 5,
                            grid: {
                                color: 'rgba(128,128,128,0.1)'
                            },
                            ticks: {
                                color: 'rgba(128,128,128,0.7)',
                                stepSize: 1
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                color: 'rgba(128,128,128,0.7)'
                            }
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
                        backgroundColor: ['rgba(16,185,129,0.8)', 'rgba(128,128,128,0.1)'],
                        borderWidth: 0,
                        hoverOffset: 6,
                    }]
                },
                options: {
                    responsive: true,
                    cutout: '72%',
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                color: 'rgba(128,128,128,0.8)',
                                padding: 16
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: ctx => ` ${ctx.parsed.toFixed(1)}%`
                            }
                        }
                    }
                }
            });
        } else {
            overallChart.data.datasets[0].data = [data.overall_percent, 100 - data.overall_percent];
            overallChart.update();
        }
    }

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

    fetchFeedback();
    setInterval(fetchFeedback, 1000);
</script>
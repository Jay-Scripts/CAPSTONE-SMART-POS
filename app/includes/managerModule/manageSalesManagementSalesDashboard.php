<div class="p-4 sm:p-6 space-y-4 bg-[var(--background-color)] min-h-screen">

    <!-- ── KPI CARDS ── -->
    <section aria-label="Store Performance Summary"
        class="grid grid-cols-1 sm:grid-cols-3 gap-4">

        <!-- Daily Sales -->
        <article class="glass-card rounded-2xl border border-[var(--container-border)] bg-[var(--glass-bg)] shadow-sm p-4 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-blue-500/15 flex items-center justify-center shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-blue-500" viewBox="0 -960 960 960" fill="currentColor">
                    <path d="M320-414v-306h120v306l-60-56-60 56Zm200 60v-526h120v406L520-354ZM120-216v-344h120v224L120-216Zm0 98 258-258 142 122 224-224h-64v-80h200v200h-80v-64L524-146 382-268 232-118H120Z" />
                </svg>
            </div>
            <div class="min-w-0">
                <p class="text-xs font-semibold uppercase tracking-wide opacity-50 text-[var(--text-color)]">Daily Sales</p>
                <h3 class="text-2xl font-bold text-[var(--text-color)] mt-0.5 truncate" id="salesAmount">₱0</h3>
            </div>
        </article>

        <!-- Daily Transactions -->
        <article class="glass-card rounded-2xl border border-[var(--container-border)] bg-[var(--glass-bg)] shadow-sm p-4 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-green-500/15 flex items-center justify-center shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-green-500" viewBox="0 -960 960 960" fill="currentColor">
                    <path d="m691-150 139-138-42-42-97 95-39-39-42 43 81 81ZM240-600h480v-80H240v80ZM720-40q-83 0-141.5-58.5T520-240q0-83 58.5-141.5T720-440q83 0 141.5 58.5T920-240q0 83-58.5 141.5T720-40ZM120-80v-680q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v267q-19-9-39-15t-41-9v-243H200v562h243q5 31 15.5 59T486-86l-6 6-60-60-60 60-60-60-60 60-60-60-60 60Zm120-200h203q3-21 9-41t15-39H240v80Zm0-160h284q38-37 88.5-58.5T720-520H240v80Zm-40 242v-562 562Z" />
                </svg>
            </div>
            <div class="min-w-0">
                <p class="text-xs font-semibold uppercase tracking-wide opacity-50 text-[var(--text-color)]">Daily Transactions</p>
                <h3 class="text-2xl font-bold text-[var(--text-color)] mt-0.5" id="transactions">0</h3>
            </div>
        </article>

        <!-- Daily Products Sold -->
        <article class="glass-card rounded-2xl border border-[var(--container-border)] bg-[var(--glass-bg)] shadow-sm p-4 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-amber-500/15 flex items-center justify-center shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-amber-500" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M8 2a1 1 0 0 0-1 1v1H5a1 1 0 0 0-.99 1.14l1.75 14A2 2 0 0 0 7.74 21h8.52a2 2 0 0 0 1.98-1.86l1.75-14A1 1 0 0 0 19 4h-2V3a1 1 0 0 0-1-1H8Zm1 2h6v1H9V4Zm-2 3h10l-1.5 12h-7L7 7Zm2.5 3a.5.5 0 1 1 0 1 .5.5 0 0 1 0-1Zm5 0a.5.5 0 1 1 0 1 .5.5 0 0 1 0-1ZM9 14a.75.75 0 1 1 0 1.5A.75.75 0 0 1 9 14Zm6 0a.75.75 0 1 1 0 1.5A.75.75 0 0 1 15 14Z" />
                </svg>
            </div>
            <div class="min-w-0">
                <p class="text-xs font-semibold uppercase tracking-wide opacity-50 text-[var(--text-color)]">Products Sold</p>
                <h3 class="text-2xl font-bold text-[var(--text-color)] mt-0.5" id="itemsSold">0</h3>
            </div>
        </article>

    </section>
    <!-- ── END KPI CARDS ── -->


    <!-- ── PERIOD SELECTOR ── -->
    <div class="flex flex-wrap items-end gap-3 p-4 rounded-2xl border border-[var(--container-border)] bg-[var(--glass-bg)]">

        <div class="flex items-center gap-2 shrink-0">
            <div class="w-8 h-8 rounded-lg bg-blue-500/15 flex items-center justify-center">
                <svg class="w-4 h-4 text-blue-500" viewBox="0 0 16 16" fill="none">
                    <rect x="2" y="3" width="12" height="11" rx="2" stroke="currentColor" stroke-width="1.5" />
                    <path d="M5 1v4M11 1v4M2 7h12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                </svg>
            </div>
            <span class="text-xs font-bold uppercase tracking-wide text-[var(--text-color)] opacity-60">Period</span>
        </div>

        <!-- Month -->
        <div class="flex flex-col gap-1">
            <label class="text-xs font-semibold uppercase tracking-wide opacity-50 text-[var(--text-color)]">Month</label>
            <div class="relative">
                <select id="monthSel"
                    class="appearance-none h-9 pl-3 pr-8 text-sm rounded-xl border border-[var(--container-border)]
                           text-[var(--text-color)] bg-[var(--background-color)]
                           cursor-pointer focus:outline-none focus:ring-2 focus:ring-blue-400 transition min-w-[140px]">
                    <option value="1">January</option>
                    <option value="2">February</option>
                    <option value="3">March</option>
                    <option value="4">April</option>
                    <option value="5">May</option>
                    <option value="6">June</option>
                    <option value="7">July</option>
                    <option value="8">August</option>
                    <option value="9">September</option>
                    <option value="10">October</option>
                    <option value="11">November</option>
                    <option value="12">December</option>
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-2.5 flex items-center">
                    <svg width="10" height="10" viewBox="0 0 12 12" fill="none">
                        <path d="M2.5 4.5L6 8L9.5 4.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Arrow divider -->
        <div class="hidden sm:flex items-center pb-0.5 opacity-30 text-[var(--text-color)]">
            <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                <path d="M3 8h10M9 4l4 4-4 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </div>

        <!-- Week -->
        <div class="flex flex-col gap-1">
            <label class="text-xs font-semibold uppercase tracking-wide opacity-50 text-[var(--text-color)]">Week</label>
            <div class="relative">
                <select id="weekSel"
                    class="appearance-none h-9 pl-3 pr-8 text-sm rounded-xl border border-[var(--container-border)]
                           text-[var(--text-color)] bg-[var(--background-color)]
                           cursor-pointer focus:outline-none focus:ring-2 focus:ring-blue-400 transition min-w-[210px]">
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-2.5 flex items-center">
                    <svg width="10" height="10" viewBox="0 0 12 12" fill="none">
                        <path d="M2.5 4.5L6 8L9.5 4.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Date range pill -->
        <div class="flex items-center gap-2 h-9 px-3 rounded-xl border border-[var(--container-border)]
                    bg-[var(--background-color)] text-sm text-[var(--text-color)] ml-auto">
            <svg class="w-3.5 h-3.5 opacity-50 shrink-0" viewBox="0 0 16 16" fill="none">
                <rect x="2" y="3" width="12" height="11" rx="2" stroke="currentColor" stroke-width="1.5" />
                <path d="M5 1v4M11 1v4M2 7h12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
            </svg>
            <span id="rangeText" class="text-xs font-semibold opacity-60">—</span>
        </div>

    </div>
    <!-- ── END PERIOD SELECTOR ── -->


    <!-- ── MAIN CHARTS ── -->
    <section class="grid grid-cols-1 lg:grid-cols-[2fr_1fr] gap-4" aria-label="Charts and Graphs">

        <!-- Left column: Sales + Top Products -->
        <div class="flex flex-col gap-4">

            <!-- Sales Overview -->
            <article class="glass-card rounded-2xl border border-[var(--container-border)] bg-[var(--glass-bg)] shadow-sm p-5 flex flex-col gap-3">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-blue-500/15 flex items-center justify-center shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-blue-500" viewBox="0 -960 960 960" fill="currentColor">
                            <path d="m136-240-56-56 296-298 160 160 208-206H640v-80h240v240h-80v-104L536-320 376-480 136-240Z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-[var(--text-color)] leading-tight">Sales Overview</h3>
                        <p class="text-xs opacity-50 text-[var(--text-color)]">Revenue trend for selected period</p>
                    </div>
                </div>
                <canvas id="ovSalesChart" height="100"></canvas>
            </article>


            <!-- Top Selling Products -->
            <article class="glass-card rounded-2xl border border-[var(--container-border)] bg-[var(--glass-bg)] shadow-sm p-5 flex flex-col gap-3">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-green-500/15 flex items-center justify-center shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-green-500" viewBox="0 -960 960 960" fill="currentColor">
                            <path d="M160-160v-320h160v320H160Zm240 0v-640h160v640H400Zm240 0v-440h160v440H640Z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-[var(--text-color)] leading-tight">Top-Selling Products</h3>
                        <p class="text-xs opacity-50 text-[var(--text-color)]">Best performers this period</p>
                    </div>
                </div>
                <canvas id="topProductsChart" height="100"></canvas>
            </article><!-- Top Cashiers -->
            <article class="glass-card rounded-2xl border border-[var(--container-border)] bg-[var(--glass-bg)] shadow-sm p-5 flex flex-col gap-3">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-amber-500/15 flex items-center justify-center shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-amber-500" viewBox="0 -960 960 960" fill="currentColor">
                            <path d="M480-480q-66 0-113-47t-47-113q0-66 47-113t113-47q66 0 113 47t47 113q0 66-47 113t-113 47ZM160-160v-112q0-34 17.5-62.5T224-378q62-31 126-46.5T480-440q66 0 130 15.5T736-378q29 15 46.5 43.5T800-272v112H160Z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-[var(--text-color)] leading-tight">Top Cashiers</h3>
                        <p class="text-xs opacity-50 text-[var(--text-color)]">Ranked by total sales</p>
                    </div>
                </div>
                <canvas id="topCashierChart" height="100"></canvas>
            </article>



        </div>

        <!-- Right column: Payment + Top Cashiers -->
        <div class="flex flex-col gap-4">

            <!-- Payment Method Breakdown -->
            <article class="glass-card rounded-2xl border border-[var(--container-border)] bg-[var(--glass-bg)] shadow-sm p-5 flex flex-col gap-3">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-purple-500/15 flex items-center justify-center shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-purple-500" viewBox="0 -960 960 960" fill="currentColor">
                            <path d="M880-720v480q0 33-23.5 56.5T800-160H160q-33 0-56.5-23.5T80-240v-480q0-33 23.5-56.5T160-800h640q33 0 56.5 23.5T880-720Zm-720 80h640v-80H160v80Zm0 160v240h640v-240H160Zm0 240v-480 480Z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-[var(--text-color)] leading-tight">Payment Methods</h3>
                        <p class="text-xs opacity-50 text-[var(--text-color)]">Cash vs E-pay breakdown</p>
                    </div>
                </div>
                <canvas id="paymentChart" height="100"></canvas>
            </article>

            <!-- Transaction Source — swapped above Top Cashiers -->
            <article class="glass-card rounded-2xl border border-[var(--container-border)] bg-[var(--glass-bg)] shadow-sm p-5 flex flex-col gap-3">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-cyan-500/15 flex items-center justify-center shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-cyan-500" viewBox="0 -960 960 960" fill="currentColor">
                            <path d="M160-120q-33 0-56.5-23.5T80-200v-560q0-33 23.5-56.5T160-840h640q33 0 56.5 23.5T880-760v560q0 33-23.5 56.5T800-120H160Zm0-80h640v-400H160v400Zm0-480h640v-80H160v80Z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-[var(--text-color)] leading-tight">Transactions by Source</h3>
                        <p class="text-xs opacity-50 text-[var(--text-color)]">POS vs Kiosk vs Rewards App</p>
                    </div>
                </div>
                <div class="flex justify-center">
                    <div style="width:200px; height:200px;">
                        <canvas id="orderSourceChart"></canvas>
                    </div>
                </div>
            </article>

        </div>

    </section>
    <!-- ── END MAIN CHARTS ── -->

</div>
  <div class="p-6 space-y-8 bg-[var(--background-color)] min-h-screen">

      <!-- KPI Cards -->
      <section aria-label="Store Performance Summary"
          class="grid portrait:grid-cols-1 sm:grid-cols-3 md:grid-cols-1 landscape:grid-cols-3 gap-6">

          <article class="glass-card bg-[var(--background-color)] p-4 rounded-lg shadow flex items-center space-x-4">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-[var(--text-color)]" viewBox="0 -960 960 960" fill="currentColor">
                  <path d="M320-414v-306h120v306l-60-56-60 56Zm200 60v-526h120v406L520-354ZM120-216v-344h120v224L120-216Zm0 98 258-258 142 122 224-224h-64v-80h200v200h-80v-64L524-146 382-268 232-118H120Z" />
              </svg>
              <div>
                  <p class="text-sm text-[var(--text-color)]">Daily Sales</p>
                  <h3 class="text-3xl font-bold text-[var(--text-color)] mt-1" id="salesAmount">₱ 0</h3>
              </div>
          </article>

          <article class="glass-card bg-[var(--background-color)] rounded-lg shadow flex items-center space-x-4 p-4">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-[var(--text-color)]" viewBox="0 -960 960 960" fill="currentColor">
                  <path d="m691-150 139-138-42-42-97 95-39-39-42 43 81 81ZM240-600h480v-80H240v80ZM720-40q-83 0-141.5-58.5T520-240q0-83 58.5-141.5T720-440q83 0 141.5 58.5T920-240q0 83-58.5 141.5T720-40ZM120-80v-680q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v267q-19-9-39-15t-41-9v-243H200v562h243q5 31 15.5 59T486-86l-6 6-60-60-60 60-60-60-60 60-60-60-60 60Zm120-200h203q3-21 9-41t15-39H240v80Zm0-160h284q38-37 88.5-58.5T720-520H240v80Zm-40 242v-562 562Z" />
              </svg>
              <div>
                  <p class="text-sm text-[var(--text-color)]">Daily Transactions</p>
                  <h3 class="text-3xl font-bold text-[var(--text-color)] mt-1" id="transactions">0</h3>
              </div>
          </article>

          <article class="glass-card bg-[var(--background-color)] rounded-lg shadow flex items-center space-x-4 p-4">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-[var(--text-color)]" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M8 2a1 1 0 0 0-1 1v1H5a1 1 0 0 0-.99 1.14l1.75 14A2 2 0 0 0 7.74 21h8.52a2 2 0 0 0 1.98-1.86l1.75-14A1 1 0 0 0 19 4h-2V3a1 1 0 0 0-1-1H8Zm1 2h6v1H9V4Zm-2 3h10l-1.5 12h-7L7 7Zm2.5 3a.5.5 0 1 1 0 1 .5.5 0 0 1 0-1Zm5 0a.5.5 0 1 1 0 1 .5.5 0 0 1 0-1ZM9 14a.75.75 0 1 1 0 1.5A.75.75 0 0 1 9 14Zm6 0a.75.75 0 1 1 0 1.5A.75.75 0 0 1 15 14Z" />
              </svg>
              <div>
                  <p class="text-sm text-[var(--text-color)]">Daily Product Sold</p>
                  <h3 class="text-3xl font-bold text-[var(--text-color)] mt-1" id="itemsSold">0</h3>
              </div>
          </article>

      </section>
      <!-- KPI Cards End -->


      <!-- ✅ ONE shared period selector — placed ONCE above all charts -->
      <div class="flex flex-col sm:flex-row sm:items-end gap-3">

          <div class="flex flex-col gap-1.5">
              <label class="text-xs font-medium tracking-wide uppercase text-gray-400">Month</label>
              <div class="relative">
                  <select id="monthSel"
                      class="appearance-none h-10 pl-3 pr-9 text-sm rounded-lg border border-[var(--border-color)] text-[var(--text-color)] bg-[var(--background-color)] cursor-pointer focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors min-w-[148px]">
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
                  <div class="pointer-events-none absolute inset-y-0 right-3 flex items-center">
                      <svg width="12" height="12" viewBox="0 0 12 12" fill="none">
                          <path d="M2.5 4.5L6 8L9.5 4.5" stroke="#9CA3AF" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                      </svg>
                  </div>
              </div>
          </div>

          <div class="pb-2 hidden sm:flex items-center text-gray-300">
              <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                  <path d="M3 8h10M9 4l4 4-4 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
              </svg>
          </div>

          <div class="flex flex-col gap-1.5">
              <label class="text-xs font-medium tracking-wide uppercase text-gray-400">Week</label>
              <div class="relative">
                  <select id="weekSel"
                      class="appearance-none h-10 pl-3 pr-9 text-sm rounded-lg border border-[var(--border-color)] text-[var(--text-color)] bg-[var(--background-color)] cursor-pointer focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors min-w-[220px]">
                  </select>
                  <div class="pointer-events-none absolute inset-y-0 right-3 flex items-center">
                      <svg width="12" height="12" viewBox="0 0 12 12" fill="none">
                          <path d="M2.5 4.5L6 8L9.5 4.5" stroke="#9CA3AF" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                      </svg>
                  </div>
              </div>
          </div>

          <div class="flex items-center gap-2 px-3 h-10 rounded-lg text-sm font-medium self-end border border-[var(--border-color)] text-[var(--text-color)] bg-[var(--background-color)]">
              <svg width="14" height="14" viewBox="0 0 16 16" fill="none" class="flex-shrink-0">
                  <rect x="2" y="3" width="12" height="11" rx="2" stroke="currentColor" stroke-width="1.5" />
                  <path d="M5 1v4M11 1v4M2 7h12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
              </svg>
              <span id="rangeText">—</span>
          </div>

      </div>
      <!-- Period Selector End -->


      <!-- Visual Analytics -->
      <section class="bg-[var(--background-color)] grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-[2fr_1fr] gap-4 mb-8">

          <section class="bg-[var(--background-color)] space-y-6" aria-label="Charts and Graphs">

              <!-- Sales Overview -->
              <article class="glass-card bg-[var(--glass-bg)] rounded-xl shadow p-6">
                  <div class="flex justify-between items-center mb-4">
                      <h3 class="flex items-center gap-2 text-lg font-semibold text-[var(--text-color)]">
                          <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-[var(--text-color)]" viewBox="0 -960 960 960" fill="currentColor">
                              <path d="m136-240-56-56 296-298 160 160 208-206H640v-80h240v240h-80v-104L536-320 376-480 136-240Z" />
                          </svg>
                          Sales Overview
                      </h3>
                  </div>
                  <canvas id="ovSalesChart" height="100"></canvas>
              </article>

              <!-- Top Selling Products -->
              <article class="glass-card bg-[var(--glass-bg)] rounded-xl shadow p-6">
                  <div class="flex justify-between items-center mb-4">
                      <h3 class="flex items-center gap-2 text-lg font-semibold text-[var(--text-color)]">
                          <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-[var(--text-color)]" viewBox="0 -960 960 960" fill="currentColor">
                              <path d="M160-160v-320h160v320H160Zm240 0v-640h160v640H400Zm240 0v-440h160v440H640Z" />
                          </svg>
                          Top-Selling Products
                      </h3>
                  </div>
                  <canvas id="topProductsChart" height="100"></canvas>
              </article>

          </section>

          <!-- Payment Breakdown -->
          <div class="space-y-6">
              <article class="glass-card bg-[var(--glass-bg)] rounded-xl shadow p-6">
                  <div class="flex items-center justify-between mb-4">
                      <h3 class="flex items-center gap-2 text-base font-semibold text-[var(--text-color)]">
                          <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-[var(--text-color)]" viewBox="0 -960 960 960" fill="currentColor">
                              <path d="M880-720v480q0 33-23.5 56.5T800-160H160q-33 0-56.5-23.5T80-240v-480q0-33 23.5-56.5T160-800h640q33 0 56.5 23.5T880-720Zm-720 80h640v-80H160v80Zm0 160v240h640v-240H160Zm0 240v-480 480Z" />
                          </svg>
                          Payment Method Breakdown
                      </h3>
                  </div>
                  <canvas id="paymentChart" height="100"></canvas>
              </article>
          </div>
          <!-- Top Cashier by Sales -->
          <article class="glass-card bg-[var(--glass-bg)] rounded-xl shadow p-6">
              <div class="flex justify-between items-center mb-4">
                  <h3 class="flex items-center gap-2 text-lg font-semibold text-[var(--text-color)]">
                      <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-[var(--text-color)]" viewBox="0 -960 960 960" fill="currentColor">
                          <path d="M480-480q-66 0-113-47t-47-113q0-66 47-113t113-47q66 0 113 47t47 113q0 66-47 113t-113 47ZM160-160v-112q0-34 17.5-62.5T224-378q62-31 126-46.5T480-440q66 0 130 15.5T736-378q29 15 46.5 43.5T800-272v112H160Z" />
                      </svg>
                      Top Cashiers by Sales
                  </h3>
              </div>
              <canvas id="topCashierChart" height="100"></canvas>
          </article>
      </section>

  </div>
  <div
      class="p-6 space-y-8 bg-[var(--background-color)] min-h-screen">
      <!-- 
      ==========================================================================================================================================
      =                                                     KPI Cards Starts Here                                                          =
      ==========================================================================================================================================
    -->
      <section
          aria-label="Store Performance Summary"
          class="grid portrait:grid-cols-1 sm:grid-cols-3 md:grid-cols-1 landscape:grid-cols-3 gap-6">
          <article class="glass-card bg-[var(--background-color)] p-4 rounded-lg shadow flex items-center space-x-4">
              <svg xmlns="http://www.w3.org/2000/svg"
                  class="h-10 w-10 text-[var(--text-color)]"
                  viewBox="0 -960 960 960"
                  fill="currentColor">
                  <path d="M320-414v-306h120v306l-60-56-60 56Zm200 60v-526h120v406L520-354ZM120-216v-344h120v224L120-216Zm0 98 258-258 142 122 224-224h-64v-80h200v200h-80v-64L524-146 382-268 232-118H120Z" />
              </svg>
              <div>
                  <p class="text-sm text-[var(--text-color)]">Daily Sales</p>
                  <h3 class="text-3xl font-bold text-[var(--text-color)] mt-1" id="salesAmount">
                      ₱ 0
                  </h3>
              </div>
          </article>


          <article class="glass-card bg-[var(--background-color)] rounded-lg shadow flex items-center space-x-4 p-4">
              <svg
                  xmlns="http://www.w3.org/2000/svg"
                  class="w-10 h-10 text-[var(--text-color)]"
                  viewBox="0 -960 960 960"
                  fill="currentColor">
                  <path d="m691-150 139-138-42-42-97 95-39-39-42 43 81 81ZM240-600h480v-80H240v80ZM720-40q-83 0-141.5-58.5T520-240q0-83 58.5-141.5T720-440q83 0 141.5 58.5T920-240q0 83-58.5 141.5T720-40ZM120-80v-680q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v267q-19-9-39-15t-41-9v-243H200v562h243q5 31 15.5 59T486-86l-6 6-60-60-60 60-60-60-60 60-60-60-60 60Zm120-200h203q3-21 9-41t15-39H240v80Zm0-160h284q38-37 88.5-58.5T720-520H240v80Zm-40 242v-562 562Z" />
              </svg>
              <div>
                  <p class="text-sm text-[var(--text-color)]">Daily Transactions</p>
                  <h3 class="text-3xl font-bold text-[var(--text-color)] mt-1" id="transactions">
                      0
                  </h3>
              </div>
          </article>


          <article class="glass-card bg-[var(--background-color)] rounded-lg shadow flex items-center space-x-4 p-4">
              <svg
                  xmlns="http://www.w3.org/2000/svg"
                  class="w-10 h-10 text-[var(--text-color)]"
                  viewBox="0 0 24 24"
                  fill="currentColor">
                  <path d="M8 2a1 1 0 0 0-1 1v1H5a1 1 0 0 0-.99 1.14l1.75 14A2 2 0 0 0 7.74 21h8.52a2 2 0 0 0 1.98-1.86l1.75-14A1 1 0 0 0 19 4h-2V3a1 1 0 0 0-1-1H8Zm1 2h6v1H9V4Zm-2 3h10l-1.5 12h-7L7 7Zm2.5 3a.5.5 0 1 1 0 1 .5.5 0 0 1 0-1Zm5 0a.5.5 0 1 1 0 1 .5.5 0 0 1 0-1ZM9 14a.75.75 0 1 1 0 1.5A.75.75 0 0 1 9 14Zm6 0a.75.75 0 1 1 0 1.5A.75.75 0 0 1 15 14Z" />
              </svg>
              <div>
                  <p class="text-sm text-[var(--text-color)]">Daily Product Sold</p>
                  <h3 class="text-3xl font-bold text-[var(--text-color)] mt-1" id="itemsSold">
                      0
                  </h3>
              </div>
          </article>

      </section>
      <!-- 
      ==========================================================================================================================================
      =                                                     KPI Cards Ends Here                                                          =
      ==========================================================================================================================================
    -->



      <!-- 
      ==========================================================================================================================================
      =                                                     Visual Analytics Starts Here                                                          =
      ==========================================================================================================================================
    -->

      <section
          class="bg-[var(--background-color)] grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-[2fr_1fr] gap-4 mb-8">
          <!--Sales Overview Starts here-->
          <section
              class="bg-[var(--background-color)] space-y-6"
              aria-label="Charts and Graphs">
              <article class="glass-card bg-[var(--glass-bg)] rounded-xl shadow p-6">
                  <div class="flex justify-between items-center mb-4">
                      <h3 class="flex items-center gap-2 text-lg font-semibold text-[var(--text-color)]">
                          <svg
                              xmlns="http://www.w3.org/2000/svg"
                              class="w-5 h-5 text-[var(--text-color)]"
                              viewBox="0 -960 960 960"
                              fill="currentColor">
                              <path d="m136-240-56-56 296-298 160 160 208-206H640v-80h240v240h-80v-104L536-320 376-480 136-240Z" />
                          </svg>
                          Weekly Sales Overview
                      </h3>


                  </div>

                  <canvas id="ovSalesChart" height="100"></canvas>
              </article>
              <!--Sales Overview Ends here-->


              <!-- Top selling Products charts Starts HERE -->
              <article class="glass-card bg-[var(--glass-bg)] rounded-xl shadow p-6">
                  <div class="flex justify-between items-center mb-4">
                      <h3 class="flex items-center gap-2 text-lg font-semibold text-[var(--text-color)]">
                          <svg
                              xmlns="http://www.w3.org/2000/svg"
                              class="w-5 h-5 text-[var(--text-color)]"
                              viewBox="0 -960 960 960"
                              fill="currentColor">
                              <path d="M160-160v-320h160v320H160Zm240 0v-640h160v640H400Zm240 0v-440h160v440H640Z" />
                          </svg>
                          Daily Top-Selling Products
                      </h3>

                  </div>

                  <canvas id="topProductsChart" height="100"></canvas>
              </article>
          </section>
          <!-- Top-Seling ends Here -->


          <!-- Payment Method Starts here -->
          <div class="space-y-6">
              <article class="glass-card bg-[var(--glass-bg)] rounded-xl shadow p-6 ">
                  <div class="flex items-center justify-between mb-4">
                      <h3 class="flex items-center gap-2 text-base font-semibold text-[var(--text-color)]">
                          <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-[var(--text-color)]" viewBox="0 -960 960 960" fill="currentColor">
                              <path d="M880-720v480q0 33-23.5 56.5T800-160H160q-33 0-56.5-23.5T80-240v-480q0-33 23.5-56.5T160-800h640q33 0 56.5 23.5T880-720Zm-720 80h640v-80H160v80Zm0 160v240h640v-240H160Zm0 240v-480 480Z" />
                          </svg>
                          Daily Payment Method Breakdown
                      </h3>
                  </div>

                  <canvas id="paymentChart" height="100"></canvas>
              </article>

              <!-- Payment method ends here -->

          </div>
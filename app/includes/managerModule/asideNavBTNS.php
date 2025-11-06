  <aside
      id="logo-sidebar"
      class="fixed top-0 left-0 z-40 w-64 h-screen transition-transform -translate-x-full sm:translate-x-0 bg-[var(--managers-nav-bg)] overflow-y-auto"
      aria-label="Sidebar">
      <section class="h-full overflow-y">
          <div class="flex flex-col items-center justify-center mb-2">
              <img
                  src="../assets/SVG/LOGO/WLOGO.svg"
                  class="h-[10vh] theme-logo"
                  alt="Module Logo" />
              <p
                  class="self-center text-xl font-semibold whitespace-nowrap text-[var(--managers-nav-text)]">
                  SMART POS
              </p>
          </div>
          <!-- 
      ==========================================================================================================================================
      =                                                                                                                                        =
      =                                         Navigation Bar - Starts Here                                                                   =
      =                                                                                                                                        =
      ==========================================================================================================================================
    -->
          <div class="text-[var(--managers-nav-text)]" id="sideBar">
              <!-- 
      ==========================================================================================================================================
      =                                                    Navigation Menu Starts Here                                                         =
      ==========================================================================================================================================
    -->
              <nav class="mt-6 px-3 mb-3 flex justify-around flex-col">
                  <!-- 
      -----------------------------------------------------------------------------------------------------------------------------------------
      -                                                  Overview Start Here                                                                  -
      -----------------------------------------------------------------------------------------------------------------------------------------
    -->
                  <!-- <div class="px-4 py-3 mt-6">
            <h3
              class="text-xs font-semibold text-[var(--managers-nav-text)] uppercase tracking-wider flex items-center">
              Dashboard
            </h3>
          </div>
          <section class="space-y-1 px-3 group">
            <a
              href=""
              data-module="overview"
              class="navItem font-medium flex items-center px-3 py-2 text-sm rounded-lg transition-opacity duration-200 group-hover:opacity-20 hover:!opacity-100">
              <svg
                class="w-5 h-5 mr-3 text-blue-400"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24">
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
              </svg>
              Overview
            </a>
          </section> -->
                  <!-- 
      -----------------------------------------------------------------------------------------------------------------------------------------
      -                                                  Overview Ends Here                                                                   -
      -----------------------------------------------------------------------------------------------------------------------------------------
    -->

                  <!-- 
      -----------------------------------------------------------------------------------------------------------------------------------------
      -                                                  Staff Management Start Here                                                          -
      -----------------------------------------------------------------------------------------------------------------------------------------
    -->
                  <div class="px-4 py-3 mt-2">
                      <h3
                          class="text-xs font-semibold text-[var(--managers-nav-text)] uppercase tracking-wider">
                          Staff Management
                      </h3>
                  </div>
                  <section class="space-y-1 px-3 group">
                      <a
                          data-module="registerStaff"
                          href="#"
                          class="navItem flex font-medium items-center px-4 py-3 text-sm rounded-lg transition-opacity duration-200 group-hover:opacity-20 hover:!opacity-100">
                          <svg
                              class="w-5 h-5 mr-3 text-cyan-400"
                              fill="none"
                              stroke="currentColor"
                              viewBox="0 0 24 24">
                              <path
                                  stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a3 3 0 11-6 0 3 3 0 016 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                          </svg>

                          Register Staff
                      </a>
                      <a
                          data-module="modifyPosition"
                          href="#"
                          class="navItem flex font-medium items-center px-3 py-2 text-sm rounded-lg cursor-pointer transition-opacity duration-200 group-hover:opacity-20 hover:!opacity-100">
                          <svg
                              class="w-5 h-5 mr-3 text-green-400"
                              fill="none"
                              stroke="currentColor"
                              viewBox="0 0 24 24">
                              <path
                                  stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M11 5l7 7-9 9H2v-7l9-9z" />
                          </svg>

                          Modify Position
                      </a>
                      <a
                          data-module="modifyStatus"
                          href="#"
                          class="navItem flex font-medium items-center px-3 py-2 text-sm rounded-lg cursor-pointer transition-opacity duration-200 group-hover:opacity-20 hover:!opacity-100">
                          <svg
                              class="w-5 h-5 mr-3 text-yellow-400"
                              fill="none"
                              stroke="currentColor"
                              viewBox="0 0 24 24">
                              <path
                                  stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M11 5l7 7-9 9H2v-7l9-9z" />
                          </svg>
                          Modify Status
                      </a>
                  </section>
                  <!-- 
      -----------------------------------------------------------------------------------------------------------------------------------------
      -                                                  Staff Management Ends Here                                                           -
      -----------------------------------------------------------------------------------------------------------------------------------------
    -->

                  <!-- 
      -----------------------------------------------------------------------------------------------------------------------------------------
      -                                                  Product Management Starts Here                                                       -
      -----------------------------------------------------------------------------------------------------------------------------------------
    -->
                  <div class="px-6 py-2 mt-2">
                      <h3
                          class="text-xs font-semibold text-[var(--managers-nav-text)] uppercase tracking-wider">
                          Product Management
                      </h3>
                  </div>
                  <section class="space-y-1 px-3 group">

                      <a
                          data-module="enableProduct"
                          href="#"
                          class="navItem font-medium flex items-center px-3 py-2 text-sm rounded-lg cursor-pointer transition-opacity duration-200 group-hover:opacity-20 hover:!opacity-100">
                          <svg
                              class="w-5 h-5 mr-3 text-green-400"
                              fill="none"
                              stroke="currentColor"
                              viewBox="0 0 24 24">
                              <path
                                  stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M5 13l4 4L19 7" />
                          </svg>

                          Enable Product
                      </a>
                      <a
                          data-module="disableProduct"
                          href="#"
                          class="navItem font-medium flex items-center px-3 py-2 text-sm rounded-lg cursor-pointer transition-opacity duration-200 group-hover:opacity-20 hover:!opacity-100">
                          <svg
                              class="w-5 h-5 mr-3 text-red-400"
                              fill="none"
                              stroke="currentColor"
                              viewBox="0 0 24 24">
                              <path
                                  stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M18.364 5.636l-12.728 12.728m0-12.728l12.728 12.728M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                          </svg>
                          Disable Product
                      </a>
                      <a
                          data-module="productMovementHistory"
                          href="#"
                          class="navItem font-medium flex items-center px-3 py-2 text-sm rounded-lg cursor-pointer transition-opacity duration-200 group-hover:opacity-20 hover:!opacity-100">
                          <svg
                              class="w-5 h-5 mr-3 text-blue-400"
                              fill="none"
                              stroke="currentColor"
                              viewBox="0 0 24 24">
                              <path
                                  stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M4 6v14h16M8 16v-4m4 4V8m4 8v-2" />
                          </svg>

                          Product Analytics
                      </a>
                  </section>

                  <!-- 
      -----------------------------------------------------------------------------------------------------------------------------------------
      -                                                  Product Management Ends Here                                                         -
      -----------------------------------------------------------------------------------------------------------------------------------------
    -->
                  <!-- 
      -----------------------------------------------------------------------------------------------------------------------------------------
      -                                                  Inventory Management Starts Here                                                     -
      -----------------------------------------------------------------------------------------------------------------------------------------
    -->
                  <div class="px-6 py-2 mt-2">
                      <h3
                          class="text-xs font-semibold text-[var(--managers-nav-text)] uppercase tracking-wider">
                          Inventory Management
                      </h3>
                  </div>
                  <section class="space-y-1 px-3 group">
                      <a
                          data-module="stockEntry"
                          href="#"
                          class="navItem font-medium flex items-center px-3 py-2 text-sm rounded-lg cursor-pointer transition-opacity duration-200 group-hover:opacity-20 hover:!opacity-100">
                          <svg
                              class="w-5 h-5 mr-3 text-emerald-400"
                              fill="currentColor"
                              viewBox="0 -960 960 960"
                              xmlns="http://www.w3.org/2000/svg">
                              <path
                                  d="M640-640h120-120Zm-440 0h338-18 14-334Zm16-80h528l-34-40H250l-34 40Zm184 270 80-40 80 40v-190H400v190Zm182 330H200q-33 0-56.5-23.5T120-200v-499q0-14 4.5-27t13.5-24l50-61q11-14 27.5-21.5T250-840h460q18 0 34.5 7.5T772-811l50 61q9 11 13.5 24t4.5 27v196q-19-7-39-11t-41-4v-122H640v153q-35 20-61 49.5T538-371l-58-29-160 80v-320H200v440h334q8 23 20 43t28 37Zm138 0v-120H600v-80h120v-120h80v120h120v80H800v120h-80Z" />
                          </svg>
                          Stock Reports
                      </a>


                      <a
                          data-module="stockLevel"
                          href="#"
                          class="navItem font-medium flex items-center px-3 py-2 text-sm rounded-lg cursor-pointer transition-opacity duration-200 group-hover:opacity-20 hover:!opacity-100">
                          <svg
                              class="w-5 h-5 mr-3 text-emerald-400"
                              fill="currentColor"
                              viewBox="0 -960 960 960"
                              xmlns="http://www.w3.org/2000/svg">
                              <path d="M640-640h120-120Zm-440 0h338-18 14-334Zm16-80h528l-34-40H250l-34 40Zm184 270 80-40 80 40v-190H400v190Zm182 330H200q-33 0-56.5-23.5T120-200v-499q0-14 4.5-27t13.5-24l50-61q11-14 27.5-21.5T250-840h460q18 0 34.5 7.5T772-811l50 61q9 11 13.5 24t4.5 27v196q-19-7-39-11t-41-4v-122H640v153q-35 20-61 49.5T538-371l-58-29-160 80v-320H200v440h334q8 23 20 43t28 37Z" />
                              <path d="M720-120v-160h-80l120-120 120 120h-80v160h-80Z" />
                          </svg>

                          Stock Control
                      </a>
                      <a
                          data-module="lowStockAlerts"
                          href="#"
                          class="navItem flex font-medium items-center px-3 py-2 text-sm rounded-lg cursor-pointer transition-opacity duration-200 group-hover:opacity-20 hover:!opacity-100">
                          <svg
                              class="w-5 h-5 mr-3 text-yellow-400"
                              fill="none"
                              stroke="currentColor"
                              viewBox="0 0 24 24">
                              <path
                                  stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                          </svg>
                          Stock Alerts
                      </a>
                      <a
                          data-module="stocksMovementHistory"
                          href="#"
                          class="navItem flex font-medium items-center px-3 py-2 text-sm rounded-lg cursor-pointer transition-opacity duration-200 group-hover:opacity-20 hover:!opacity-100">
                          <svg
                              class="w-5 h-5 mr-3 text-red-600"
                              fill="none"
                              stroke="currentColor"
                              viewBox="0 0 24 24">
                              <path
                                  stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                          </svg>
                          Stock Logs History
                      </a>
                  </section>
                  <!-- 
      -----------------------------------------------------------------------------------------------------------------------------------------
      -                                                  Inventory Management Ends Here                                                       -
      -----------------------------------------------------------------------------------------------------------------------------------------
    -->

                  <!-- 
      -----------------------------------------------------------------------------------------------------------------------------------------
      -                                                  Sales Management Starts Here                                                         -
      -----------------------------------------------------------------------------------------------------------------------------------------
    -->
                  <div class="px-4 py-3 mt-2">
                      <h3
                          class="text-xs font-semibold text-[var(managers-nav-text)] uppercase tracking-wider">
                          Sales Management
                      </h3>
                  </div>
                  <section class="space-y-1 px-3 group">
                      <a
                          data-module="salesReports"
                          href="#"
                          class="navItem font-medium flex items-center px-3 py-2 text-sm rounded-lg cursor-pointer transition-opacity duration-200 group-hover:opacity-20 hover:!opacity-100">
                          <svg
                              class="w-5 h-5 mr-3 text-purple-400"
                              fill="none"
                              stroke="currentColor"
                              viewBox="0 0 24 24">
                              <path
                                  stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                          </svg>

                          Sales Reports
                      </a>
                      <a
                          data-module="performanceTrend"
                          href="#"
                          class="navItem font-medium flex items-center px-3 py-2 text-sm rounded-lg cursor-pointer transition-opacity duration-200 group-hover:opacity-20 hover:!opacity-100">
                          <svg
                              class="w-5 h-5 mr-3 text-orange-400"
                              fill="none"
                              stroke="currentColor"
                              viewBox="0 0 24 24">
                              <path
                                  stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                          </svg>

                          Sales Dashboard
                      </a>
                      <a
                          data-module="refund"
                          href="#"
                          class="navItem font-medium flex items-center px-3 py-2 text-sm rounded-lg cursor-pointer transition-opacity duration-200 group-hover:opacity-20 hover:!opacity-100">
                          <svg
                              class="w-5 h-5 mr-3 text-red-400"
                              fill="none"
                              stroke="currentColor"
                              viewBox="0 0 24 24">
                              <path
                                  stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path>
                          </svg>
                          Refund
                      </a>
                      <a
                          data-module="logWaste"
                          href="#"
                          class="navItem font-medium flex items-center px-3 py-2 text-sm rounded-lg cursor-pointer transition-opacity duration-200 group-hover:opacity-20 hover:!opacity-100">
                          <svg
                              class="w-5 h-5 mr-3 text-red-400"
                              fill="none"
                              stroke="currentColor"
                              viewBox="0 0 24 24">
                              <path
                                  stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                          </svg>
                          Log Waste
                      </a>
                  </section>
                  <!-- 
      -----------------------------------------------------------------------------------------------------------------------------------------
      -                                                  Sales Management Ends Here                                                           -
      -----------------------------------------------------------------------------------------------------------------------------------------
    -->


                  <!-- 
      -----------------------------------------------------------------------------------------------------------------------------------------
      -                                                  Customer Management Starts Here                                                        -
      -----------------------------------------------------------------------------------------------------------------------------------------
    -->
                  <div class="px-6 py-2 mt-2">
                      <h3
                          class="text-xs font-semibold text-[var(--managers-nav-text)] uppercase tracking-wider">
                          Customer Management
                      </h3>
                  </div>
                  <section class="space-y-1 px-3 group">
                      <a
                          data-module="satisfactionDashboard"
                          href="#"
                          class="navItem font-medium flex items-center px-3 py-2 text-sm rounded-lg cursor-pointer transition-opacity duration-200 group-hover:opacity-20 hover:!opacity-100">
                          <svg
                              class="w-5 h-5 mr-3 text-green-400"
                              fill="none"
                              stroke="currentColor"
                              viewBox="0 0 24 24">
                              <path
                                  stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                          </svg>
                          Satisfaction Dashboard
                      </a>
                      <a
                          data-module="complaintsManagement"
                          href="#"
                          class="navItem font-medium flex items-center px-3 py-2 text-sm rounded-lg cursor-pointer transition-opacity duration-200 group-hover:opacity-20 hover:!opacity-100">
                          <svg
                              class="w-5 h-5 mr-3 text-amber-400"
                              fill="none"
                              stroke="currentColor"
                              viewBox="0 0 24 24">
                              <path
                                  stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                          </svg>
                          Complaint Management
                      </a>
                      <a
                          data-module="rewards&LoyaltyProgram"
                          href="#"
                          class="navItem font-medium flex items-center px-3 py-2 text-sm rounded-lg cursor-pointer transition-opacity duration-200 group-hover:opacity-20 hover:!opacity-100">
                          <svg
                              class="w-5 h-5 mr-3 text-violet-400"
                              fill="none"
                              stroke="currentColor"
                              viewBox="0 0 24 24">
                              <path
                                  stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                          </svg>
                          Rewards & Loyalty Program
                      </a>
                      <a
                          data-module="discountDashboard"
                          href="#"
                          class="navItem flex items-center px-3 py-2 text-sm rounded-lg cursor-pointer transition-colors duration-200 group-hover:opacity-10 hover:!opacity-150 hover:bg-yellow-800 hover:text-white">
                          <svg
                              class="w-5 h-5 mr-3 text-rose-400"
                              fill="none"
                              stroke="currentColor"
                              viewBox="0 0 24 24">
                              <path
                                  stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                          </svg>

                          Discount Dashboard
                      </a>
                  </section>
                  <!-- 
      -----------------------------------------------------------------------------------------------------------------------------------------
      -                                                  Customer Management Ends Here                                                        -
      -----------------------------------------------------------------------------------------------------------------------------------------
    -->
              </nav>
          </div>

          <!-- 
      ==========================================================================================================================================
      =                                                                                                                                        =
      =                                         Navigation Bar - Ends Here                                                                     =
      =                                                                                                                                        =
      ==========================================================================================================================================
    -->
      </section>
  </aside>
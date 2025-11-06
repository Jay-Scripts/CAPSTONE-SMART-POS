  <!-- =============================================
                 DASHBOARD CONTENT CONTAINER STARTS HERE
                 ================================================= -->
  <div class="p-4 sm:p-6 sm:space-y-8 bg-[var(--background-color)]">
      <!-- ====================
           Cards starts Here
           ======================= -->
      <section
          class="bg-[var(--background-color)] grid grid-cols-1 xl:grid-cols-2 sm:gap-6"
          aria-label="overview charts">
          <!-- =============================================
               Category's Percentage per sales charts Starts here
               ================================================= -->
          <article
              class="glass-card rounded-xl shadow-lg p-4 sm:p-6 bg-[var(--glass-bg)] ease-in-out">
              <div
                  class="flex flex-col sm:flex-row sm:items-center justify-between mb-4 gap-2">
                  <h2 class="text-lg sm:text-xl font-semibold ">
                      Sold Product by Category
                  </h2>
                  <div class="flex items-center gap-2 text-sm text-gray-500">

                  </div>
              </div>
              <div class="relative h-64 sm:h-80">
                  <canvas
                      id="soldProductByCategoryChart"
                      class="max-h-full"></canvas>
              </div>
          </article>

          <!-- =============================================
               Category's Percentage per sales charts Ends here
               ================================================= -->
          <!-- =============================================
               Promos charts Starts here
               ================================================= -->

          <article
              class="glass-card bg-[var(--glass-bg)] rounded-xl shadow-lg p-4 sm:p-6 ease-in-out">
              <div
                  class="flex flex-col sm:flex-row sm:items-center justify-between mb-4 gap-2">
                  <h2 class="text-lg sm:text-xl font-semibold ">
                      Sold Addons
                  </h2>
              </div>
              <div class="relative h-64 sm:h-80">
                  <canvas id="soldAddOnsChart" class="max-h-full"></canvas>
              </div>
          </article>

          <!-- =============================================
               Promos charts Ends here
               ================================================= -->
      </section>
      <!-- =============================================
               Category charts Starts here
               ================================================= -->
      <section
          class="bg-[var(--background-color)] grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3  sm:gap-6"
          aria-label="Category charts">
          <!-- =============================================
               Iced Coffee charts Starts here
               ================================================= -->
          <article
              class="glass-card bg-[var(--glass-bg)] rounded-xl shadow-lg p-4 sm:p-6 ease-in-out">
              <div
                  class="flex flex-col sm:flex-row sm:items-center justify-between mb-4 gap-2">
                  <div class="flex items-center gap-2">
                      <div class="w-3 h-3 bg-blue-300 rounded-full"></div>
                      <h3
                          class="text-base sm:text-lg font-semibold ">
                          Iced Coffee
                      </h3>
                  </div>

              </div>
              <div class="relative h-[300px] w-full sm:h-64">
                  <canvas id="srIcedCoffeeChart"></canvas>
              </div>

          </article>

          <!-- =============================================
               Iced Coffee charts Ends here
               ================================================= -->

          <!-- =================================
                      Fruit Tea charts Starts here
               ======================================= -->
          <article
              class="glass-card bg-[var(--glass-bg)] rounded-xl shadow-lg sm:p-6 ease-in-out">
              <div
                  class=" flex flex-col sm:flex-row sm:items-center justify-between mb-4 gap-2">
                  <div class="flex items-center gap-2">
                      <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                      <h3
                          class="text-base sm:text-lg font-semibold ">
                          Fruit Tea
                      </h3>
                  </div>

              </div>
              <div class="relative h-[300px] w-full sm:h-64">
                  <canvas id="srFruitteaChart"></canvas>
              </div>

          </article>
          <!-- =============================================
               Fruit Tea charts ends here
               ================================================= -->

          <!-- =================================
               Hot Brew charts Starts here
               ======================================= -->
          <article
              class="glass-card bg-[var(--glass-bg)] rounded-xl shadow-lg p-4 sm:p-6 ease-in-out">
              <div
                  class="flex flex-col sm:flex-row sm:items-center justify-between mb-4 gap-2">
                  <div class="flex items-center gap-2">
                      <div class="w-3 h-3 bg-yellow-500 rounded-full"></div>
                      <h3
                          class="text-base sm:text-lg font-semibold ">
                          Hot Brew
                      </h3>
                  </div>

              </div>
              <div class="relative h-[300px] w-full sm:h-64">
                  <canvas id="srHotbrewChart"></canvas>
              </div>

          </article>
          <!-- =============================================
               Hot Brew charts ends here
               ================================================= -->

          <!-- =============================================
               Category charts Starts here
               ================================================= -->

          <!-- =================================
               Praf charts Starts here
               ======================================= -->
          <article
              class="glass-card bg-[var(--glass-bg)] rounded-xl shadow-lg p-4 sm:p-6 ease-in-out">
              <div
                  class="flex flex-col sm:flex-row sm:items-center justify-between mb-4 gap-2">
                  <div class="flex items-center gap-2">
                      <div class="w-3 h-3 bg-amber-600 rounded-full"></div>
                      <h3
                          class="text-base sm:text-lg font-semibold ">
                          Praf
                      </h3>
                  </div>

              </div>
              <div class="relative h-[300px] w-full sm:h-64">
                  <canvas id="srPrafChart"></canvas>
              </div>

          </article>
          <!-- =============================================
               Praf charts ends here
               ================================================= -->

          <!-- =========================================
             Milktea charts Starts Here
             ============================================== -->
          <article
              class="glass-card bg-[var(--glass-bg)] rounded-xl shadow-lg p-4 sm:p-6 fade-in">
              <div
                  class="flex flex-col sm:flex-row sm:items-center justify-between mb-4 gap-2">
                  <div class="flex items-center gap-2">
                      <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                      <h3
                          class="text-base sm:text-lg font-semibold ">
                          Milk Tea
                      </h3>
                  </div>

              </div>
              <div class="relative h-[300px] w-full sm:h-64">
                  <canvas id="srMilkteaChart"></canvas>
              </div>

          </article>

          <!-- =================================
                       Milktea charts ends here
               ======================================= -->

          <!-- =============================================
               Brosty charts Starts here
               ================================================= -->
          <article
              class="glass-card bg-[var(--glass-bg)] rounded-xl shadow-lg p-4 sm:p-6 ease-in-out">
              <div
                  class="flex flex-col sm:flex-row sm:items-center justify-between mb-4 gap-2">
                  <div class="flex items-center gap-2">
                      <div class="w-3 h-3 bg-blue-300 rounded-full"></div>
                      <h3
                          class="text-base sm:text-lg font-semibold ">
                          Iced Coffee
                      </h3>
                  </div>

              </div>
              <div class="relative h-[300px] w-full sm:h-64">
                  <canvas id="srBrostyChart"></canvas>
              </div>

          </article>

          <!-- =============================================
               Brosty charts Ends here
               ================================================= -->
      </section>

      <!-- =============================================
               Praf/Iced Coffee charts Ends here
               ================================================= -->
  </div>
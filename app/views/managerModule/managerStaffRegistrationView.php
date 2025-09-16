          <?php
            include "../../app/controllers/managerModule/managerStaffRegistrationContrl.php";
            ?>

          <div class="flex items-center justify-center p-4 lg:p-8 scaleIn">
              <div class="glass-card rounded-2xl shadow-lg p-8 lg:p-10">
                  <div
                      class="w-16 h-16 mx-auto mb-4 flex items-center justify-center">
                      <img
                          src="../../public/assets/SVG/LOGO/BLOGO.svg"
                          alt="Logo Icon"
                          class="h-20 w-35 theme-logo" />
                  </div>
                  <h2
                      class="text-2xl lg:text-3xl font-bold text-center text-[var(--text-color)] mb-2">
                      Staff Registraton
                  </h2>
                  <p
                      class="text-[var(--text-color)] text-sm lg:text-base font-medium text-center mb-4">
                      Create a new staff member account
                  </p>
                  <form
                      id="staffRegistrationForm"
                      class="space-y-6 lg:space-x-8"
                      action=""
                      method="POST">
                      <div
                          class="space-y-3 animate-fade-in delay-100 animation-fill-both">
                          <label
                              for="staffName"
                              class="flex items center gap-2 text-sm lg:text-base font-semibold text-[var(--text-color)] transition-colors duration-200">
                              <svg
                                  class="w-4 h-4 lg:h-5 lg:w-5 text-[var(--text-color)]"
                                  fill="none"
                                  stroke="currentColor"
                                  viewBox="0 0 24 24">
                                  <path
                                      stroke-linecap="round"
                                      stroke-linejoin="round"
                                      stroke-width="2"
                                      d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                              </svg>
                              Staff Name :</label>
                          <div class="relative group">
                              <input
                                  type="text"
                                  name="staffName"
                                  id="staffName"
                                  placeholder="Enter name"
                                  oninput="this.value = this.value
    .replace(/[^A-Za-z ]/g, '')
    .replace(/\s{2,}/g, ' ')
    .replace(/^\s+/g, '')"
                                  class="w-full px-4 py-3 text-sm sm:text-base border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 transition-all duration-200 hover:border-gray-300 placeholder:text-gray-400 font-medium outline-none focus:-translate-y-0.5"
                                  required
                                  maxlength="30"
                                  autocomplete="name"
                                  autofocus
                                  value="<?php echo htmlspecialchars($sanitizedStaffName) ?>" />

                              <div
                                  class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
                                  <svg
                                      class="size-5 text-[var(--text-color)] group-focus-within:text-blue-500 transition-colors duration-200"
                                      fill="none"
                                      stroke="currentColor"
                                      viewBox="0 0 24 24">
                                      <path
                                          stroke-linecap="round"
                                          stroke-linejoin="round"
                                          stroke-width="2"
                                          d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                  </svg>
                              </div>
                          </div>
                          <p id="staffNameFeedback" class="text-sm mt-1"></p>
                          <p>
                              <?php echo $staffRegistrationMessage['staffName']; ?>
                          </p>
                      </div>
                      <div class="space-y-4">
                          <label
                              for="roleBarista"
                              class="flex items-center gap-2 text-sm lg:text-base font-semibold text-[var(--text-color)]">
                              <svg
                                  class="w-6 h-6 text-[var(--text-color)]"
                                  fill="none"
                                  stroke="currentColor"
                                  stroke-width="1.5"
                                  viewBox="0 0 24 24"
                                  xmlns="http://www.w3.org/2000/svg">
                                  <path
                                      stroke-linecap="round"
                                      stroke-linejoin="round"
                                      d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4z" />
                                  <path
                                      stroke-linecap="round"
                                      stroke-linejoin="round"
                                      d="M6 20c0-2.21 2.69-4 6-4s6 1.79 6 4" />
                                  <path
                                      stroke-linecap="round"
                                      stroke-linejoin="round"
                                      d="M16.5 19.5l1.5 1.5 3-3" />
                              </svg>

                              Assign Role
                          </label>
                          <section
                              class="grid grid-cols-1 sm:grid-cols-3 lg:gap-4 gap-3">
                              <div class="rolePositionBarista">
                                  <input
                                      type="radio"
                                      id="roleBarista"
                                      name="role"
                                      value="barista"
                                      class="hidden peer"
                                      <?php echo ($role === 'barista') ? 'checked' : ''; ?> />
                                  <label
                                      for="roleBarista"
                                      class="block p-4 glass-card border-gray-200 rounded-2xl cursor-pointer hover:border-orange-400 hover:shadow-lg peer-checked:!border-orange-500 peer-checked:!bg-orange-500/20 peer-checked:!shadow-xl transition-all duration-200 transform hover:-translate-y-1">
                                      <div
                                          class="w-10 h-10 lg:w-12 lg:h-12 rounded-xl mx-auto mb-3 flex items-center justify-center shadow-md">
                                          <svg
                                              class="w-6 h-6 text-[var(--text-color)]"
                                              fill="none"
                                              stroke="currentColor"
                                              stroke-width="1.5"
                                              viewBox="0 0 24 24"
                                              xmlns="http://www.w3.org/2000/svg">
                                              <path
                                                  stroke-linecap="round"
                                                  stroke-linejoin="round"
                                                  d="M5 8h12a2 2 0 012 2v3a6 6 0 01-6 6H9a6 6 0 01-6-6v-3a2 2 0 012-2z" />
                                              <path
                                                  stroke-linecap="round"
                                                  stroke-linejoin="round"
                                                  d="M19 11h1a2 2 0 010 4h-1" />
                                              <path
                                                  stroke-linecap="round"
                                                  stroke-linejoin="round"
                                                  d="M8 4c0 .5.5 1 1 1s1-.5 1-1-.5-1-1-1-1 .5-1 1zm4 0c0 .5.5 1 1 1s1-.5 1-1-.5-1-1-1-1 .5-1 1z" />
                                          </svg>
                                      </div>
                                      <h3
                                          class="font-bold text-center text-[var(--text-color)] text-sm lg:text-base">
                                          Barista
                                      </h3>
                                      <p
                                          class="text-xs text-center text-[var(--text-color)] mt-1">
                                          Prepares Drinks.
                                      </p>
                                  </label>
                              </div>
                              <div class="rolePositionCashier">
                                  <input
                                      type="radio"
                                      id="roleCashier"
                                      name="role"
                                      value="cashier"
                                      class="hidden peer"
                                      <?php echo ($role === 'cashier') ? 'checked' : ''; ?> />
                                  <label
                                      for="roleCashier"
                                      class="block p-4 glass-card border-2 border-gray-200 rounded-2xl cursor-pointer hover:border-green-400 hover:shadow-lg peer-checked:!border-green-500 peer-checked:!bg-green-500/20 peer-checked:!shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                                      <div
                                          class="w-10 h-10 lg:w-12 lg:h-12 rounded-xl mx-auto mb-3 flex items-center justify-center shadow-md">
                                          <svg
                                              class="w-6 h-6 text-[var(--text-color)]"
                                              fill="none"
                                              stroke="currentColor"
                                              stroke-width="1.5"
                                              viewBox="0 0 24 24"
                                              xmlns="http://www.w3.org/2000/svg">
                                              <circle
                                                  cx="12"
                                                  cy="6"
                                                  r="3"
                                                  stroke="currentColor"
                                                  stroke-width="1.5" />
                                              <rect
                                                  x="4"
                                                  y="14"
                                                  width="16"
                                                  height="6"
                                                  rx="1"
                                                  stroke="currentColor"
                                                  stroke-width="1.5" />
                                              <rect
                                                  x="16"
                                                  y="11"
                                                  width="3"
                                                  height="3"
                                                  rx="0.5"
                                                  stroke="currentColor"
                                                  stroke-width="1.5" />
                                              <path
                                                  stroke-linecap="round"
                                                  stroke-linejoin="round"
                                                  d="M9 14v-2a3 3 0 016 0v2" />
                                          </svg>
                                      </div>
                                      <h3
                                          class="font-bold text-center text-[var(--text-color)] text-sm lg:text-base">
                                          Cashier
                                      </h3>
                                      <p
                                          class="text-xs text-center text-[var(--text-color)] mt-1">
                                          Sales and Transactions
                                      </p>
                                  </label>
                              </div>
                              <div class="rolePositionManager">
                                  <input
                                      type="radio"
                                      id="roleManager"
                                      name="role"
                                      value="manager"
                                      class="hidden peer"
                                      <?php echo ($role === 'manager') ? 'checked' : ''; ?> />
                                  <label
                                      for="roleManager"
                                      class="block p-4 glass-card border-2 border-gray-200 rounded-2xl cursor-pointer hover:border-purple-400 hover:shadow-lg peer-checked:!border-purple-500 peer-checked:!bg-purple-500/20 peer-checked:!shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                                      <div
                                          class="w-10 h-10 lg:w-12 lg:h-12 rounded-xl mx-auto mb-3 flex items-center justify-center shadow-md">
                                          <svg
                                              class="w-6 h-6 text-[var(--text-color)]"
                                              fill="none"
                                              stroke="currentColor"
                                              stroke-width="1.5"
                                              viewBox="0 0 24 24"
                                              xmlns="http://www.w3.org/2000/svg">
                                              <circle cx="12" cy="6" r="3" />

                                              <path
                                                  stroke-linecap="round"
                                                  stroke-linejoin="round"
                                                  d="M6 20v-2a6 6 0 0112 0v2H6z" />

                                              <path
                                                  stroke-linecap="round"
                                                  stroke-linejoin="round"
                                                  d="M12 9v2l-1 1 1 1 1-1-1-1V9z" />
                                          </svg>
                                      </div>
                                      <h3
                                          class="font-bold text-center text-[var(--text-color)] text-sm lg:text-base">
                                          Manager
                                      </h3>
                                      <p
                                          class="text-xs text-center text-[var(--text-color)] mt-1">
                                          Supervises Staff
                                      </p>
                                  </label>
                              </div>
                          </section>
                          <p>
                              <?php echo $staffRegistrationMessage['role']; ?>
                          </p>
                      </div>
                      <!-- <input
                    type="hidden"
                    name="manager_account"
                    value="<?php echo htmlspecialchars($managerAccount); ?>" /> -->

                      <button
                          name="registerStaff"
                          type="submit"
                          id="submitBtn"
                          class="w-full bg-gradient-to-r from-blue-600 to-blue-700 text-white font-semibold py-3.5 px-6 rounded-xl hover:from-blue-700 hover:to-blue-800 focus:ring-4 focus:ring-blue-500/50 transition-all duration-300 hover:-translate-y-1 hover:shadow-xl focus:outline-none active:translate-y-0 relative overflow-hidden group animate-fade-in delay-300 animation-fill-both text-sm sm:text-base lg:text-lg">

                          <div
                              class="absolute inset-0 -translate-x-full group-hover:translate-x-full transition-transform duration-1000 bg-gradient-to-r from-transparent via-white/20 to-transparent"></div>
                          Register Staff

                      </button>
                      <p>
                      </p>
                  </form>
              </div>

          </div>
          <?php echo $registerStaffSuccess; ?>
<div class="flex justify-center p-4 sm:p-6 lg:p-10 bg-[var(--bg-color)]">
    <form id="staffStatusForm" class="glass-card w-full sm:w-[90%] md:w-[70%] lg:w-[50%] rounded-2xl shadow-lg p-6 sm:p-8 lg:p-10 transition-all">
        <!-- Logo -->
        <div class="w-16 h-16 mx-auto mb-4 flex items-center justify-center">
            <img src="../assets/SVG/LOGO/BLOGO.svg" alt="Logo Icon" class="h-16 w-auto theme-logo" />
        </div>

        <!-- Header -->
        <h2 class="text-xl sm:text-2xl lg:text-3xl font-bold text-center text-[var(--text-color)] mb-2">
            Modify Staff Status
        </h2>
        <p class="text-[var(--text-color)] text-xs sm:text-sm lg:text-base font-medium text-center mb-4">
            Update or deactivate an employee account.
        </p>

        <!-- Staff ID -->
        <fieldset class="space-y-3">
            <legend class="text-base sm:text-lg font-semibold text-gray-800 flex items-center gap-2">
                <span class="h-4 w-1 bg-indigo-500 rounded"></span>
                Staff Identification
            </legend>
            <p class="text-xs sm:text-sm text-gray-500">Provide the staff ID to locate their record.</p>

            <label class="block mt-1">
                <span class="block text-sm font-medium text-gray-700">
                    Staff ID <span class="text-red-500">*</span>
                </span>
                <input
                    type="text"
                    name="staffID"
                    id="staffID"
                    maxlength="30"
                    required
                    placeholder="Enter Staff ID Number"
                    class="w-full mt-1 border rounded-lg border-gray-300 px-3 sm:px-4 py-2 sm:py-2.5 text-gray-800 text-sm sm:text-base focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition" />
            </label>
            <p id="staffIDFeedback" class="text-sm text-gray-500"></p>
        </fieldset>

        <!-- Status -->
        <fieldset class="space-y-3 mt-6">
            <legend class="text-base sm:text-lg font-semibold text-gray-800 flex items-center gap-2">
                <span class="h-4 w-1 bg-indigo-500 rounded"></span>
                Update Staff Status
            </legend>
            <p class="text-xs sm:text-sm text-gray-500">Choose the new status for this staff account.</p>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                <!-- Active -->
                <label class="cursor-pointer">
                    <input type="radio" name="staffStatus" value="active" class="peer hidden" required />
                    <div class="rounded-lg border border-gray-300 px-3 py-3 sm:py-4 text-gray-700 text-sm sm:text-base peer-checked:border-green-500 peer-checked:ring-2 peer-checked:ring-green-400 peer-checked:bg-green-50 transition">
                        <div class="flex items-center gap-2 font-semibold">
                            Active
                        </div>
                    </div>
                </label>

                <!-- Inactive -->
                <label class="cursor-pointer">
                    <input type="radio" name="staffStatus" value="inactive" class="peer hidden" />
                    <div class="rounded-lg border border-gray-300 px-3 py-3 sm:py-4 text-gray-700 text-sm sm:text-base peer-checked:border-red-500 peer-checked:ring-2 peer-checked:ring-red-400 peer-checked:bg-red-50 transition">
                        <div class="flex items-center gap-2 font-semibold">
                            Inactive
                        </div>
                    </div>
                </label>
            </div>
        </fieldset>

        <!-- Hidden Manager -->
        <input type="hidden" name="manager_account" />

        <!-- Submit -->
        <div class="flex pt-6">
            <button type="submit" name="submit" id="submitBtn" class="w-full rounded-lg bg-indigo-600 px-6 py-2.5 text-sm sm:text-base font-semibold text-white shadow hover:bg-indigo-700 hover:scale-[1.02] transition-transform focus:ring-2 focus:ring-indigo-500">
                Update Status
            </button>
        </div>

        <!-- Message -->
        <p id="statusMessage" class="text-center text-sm mt-3"></p>
    </form>
</div>
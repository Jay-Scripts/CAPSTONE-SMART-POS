   <section class="mx-auto max-w-3xl px-4 py-10">
       <form
           id="wasteForm"
           method="POST"
           class="rounded-2xl bg-white border border-gray-200 p-8 shadow-lg space-y-8 transition-all hover:shadow-xl">

           <!-- ============================== Transaction Reference ============================== -->
           <fieldset class="space-y-3">
               <legend class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                   <span class="h-4 w-1 bg-indigo-500 rounded"></span>
                   Transaction Reference
               </legend>
               <p class="text-sm text-gray-500">
                   Link this waste record to an existing transaction.
               </p>

               <label class="block mt-1">
                   <span class="block text-sm font-medium text-gray-700">
                       Transaction ID <span class="text-red-500">*</span>
                   </span>
                   <input
                       type="number"
                       name="REG_TRANSACTION_ID"
                       required
                       placeholder="Enter Transaction ID"
                       class="w-full mt-1 border rounded-lg border-gray-300 px-4 py-2.5 text-gray-800 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition" />
               </label>
           </fieldset>

           <!-- ============================== Reason for Waste ============================== -->
           <fieldset class="space-y-3">
               <legend class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                   <span class="h-4 w-1 bg-indigo-500 rounded"></span>
                   Reason for Waste
               </legend>
               <p class="text-sm text-gray-500">Select the main reason for this waste.</p>

               <div class="grid gap-3 sm:grid-cols-2">
                   <label class="cursor-pointer">
                       <input type="radio" name="reason" value="Wrong Order" class="peer hidden" required />
                       <div class="rounded-lg border border-gray-300 px-3 py-3 text-gray-700 peer-checked:border-indigo-500 peer-checked:ring-2 peer-checked:ring-indigo-400 peer-checked:bg-indigo-50 transition">
                           Wrong Order
                       </div>
                   </label>

                   <label class="cursor-pointer">
                       <input type="radio" name="reason" value="Customer Cancelled" class="peer hidden" />
                       <div class="rounded-lg border border-gray-300 px-3 py-3 text-gray-700 peer-checked:border-indigo-500 peer-checked:ring-2 peer-checked:ring-indigo-400 peer-checked:bg-indigo-50 transition">
                           Customer Cancelled
                       </div>
                   </label>

                   <label class="cursor-pointer">
                       <input type="radio" name="reason" value="Staff Error" class="peer hidden" />
                       <div class="rounded-lg border border-gray-300 px-3 py-3 text-gray-700 peer-checked:border-indigo-500 peer-checked:ring-2 peer-checked:ring-indigo-400 peer-checked:bg-indigo-50 transition">
                           Staff Error
                       </div>
                   </label>

                   <label class="cursor-pointer">
                       <input type="radio" name="reason" value="Expired" class="peer hidden" />
                       <div class="rounded-lg border border-gray-300 px-3 py-3 text-gray-700 peer-checked:border-indigo-500 peer-checked:ring-2 peer-checked:ring-indigo-400 peer-checked:bg-indigo-50 transition">
                           Expired
                       </div>
                   </label>

                   <label class="cursor-pointer">
                       <input type="radio" name="reason" value="Free Drink / Complimentary" class="peer hidden" />
                       <div class="rounded-lg border border-gray-300 px-3 py-3 text-gray-700 peer-checked:border-indigo-500 peer-checked:ring-2 peer-checked:ring-indigo-400 peer-checked:bg-indigo-50 transition">
                           Free Drink / Complimentary
                       </div>
                   </label>

                   <label class="cursor-pointer">
                       <input type="radio" name="reason" value="Product Test" class="peer hidden" />
                       <div class="rounded-lg border border-gray-300 px-3 py-3 text-gray-700 peer-checked:border-indigo-500 peer-checked:ring-2 peer-checked:ring-indigo-400 peer-checked:bg-indigo-50 transition">
                           Product Test
                       </div>
                   </label>

                   <label class="cursor-pointer">
                       <input type="radio" name="reason" value="Others" class="peer hidden" />
                       <div class="rounded-lg border border-gray-300 px-3 py-3 text-gray-700 peer-checked:border-indigo-500 peer-checked:ring-2 peer-checked:ring-indigo-400 peer-checked:bg-indigo-50 transition">
                           Others
                       </div>
                   </label>
               </div>
           </fieldset>

           <!-- ============================== Additional Notes ============================== -->
           <fieldset class="space-y-3">
               <legend class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                   <span class="h-4 w-1 bg-indigo-500 rounded"></span>
                   Additional Notes
               </legend>
               <p class="text-sm text-gray-500">Optional: clarify the waste transaction.</p>
               <textarea
                   name="notes"
                   rows="3"
                   placeholder="e.g., Customer changed order after payment, item already prepared..."
                   class="w-full border rounded-lg border-gray-300 px-4 py-2.5 text-gray-800 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"></textarea>
           </fieldset>

           <!-- ============================== Submit Button ============================== -->
           <div class="flex items-center justify-end pt-6">
               <button
                   type="submit"
                   name="logWaste"
                   class="rounded-lg bg-red-600 px-6 py-2.5 text-sm font-semibold text-white shadow hover:bg-red-700 hover:scale-[1.02] transition-transform focus:ring-2 focus:ring-red-500">
                   Log as Waste
               </button>
           </div>

           <!-- Message Container -->
           <p id="wasteMessage" class="text-center text-sm mt-3"></p>
       </form>
   </section>
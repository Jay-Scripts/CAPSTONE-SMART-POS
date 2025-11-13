<!-- Reprint Receipt Card -->
<div class="glass-card bg-[var(--glass-bg)] rounded-xl shadow p-6 hover:shadow-2xl transition flex flex-col justify-between">
    <h3 class="text-lg font-semibold text-[var(--text-color)] flex items-center gap-2">
        <i class="fa-solid fa-receipt text-orange-500"></i> Reprint Receipt
    </h3>
    <button onclick="openReprintModal()"
        class="mt-4 bg-orange-500 hover:bg-orange-600 text-white rounded-lg px-4 py-2 transition-all duration-200">
        Reprint
    </button>
</div>

<!-- Reprint Modal -->
<div id="reprintModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
    <div class="bg-[var(--calc-bg-btn)] rounded-xl p-6 w-11/12 sm:w-96 shadow-lg">
        <h3 class="text-xl font-semibold text-[var(--text-color)] mb-4">Reprint Receipt</h3>

        <!-- Improved Dropdown -->
        <div class="relative mb-4">
            <select id="regTransId"
                class="border border-[var(--border-color)] bg-[var(--calc-bg-btn)] rounded-lg px-3 py-2 w-full text-[var(--text-color)] 
                focus:ring-2 focus:ring-orange-500 outline-none appearance-none cursor-pointer max-h-48 overflow-y-auto">
                <option value="">Select Transaction</option>
            </select>
            <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none">
                <i class="fa-solid fa-chevron-down"></i>
            </span>
        </div>

        <div class="flex justify-end gap-2">
            <button onclick="closeReprintModal()"
                class="px-4 py-2 rounded-lg bg-gray-500 hover:bg-gray-600 text-white transition">Cancel</button>
            <button onclick="reprintReceipt()"
                class="px-4 py-2 rounded-lg bg-orange-500 hover:bg-orange-600 text-white transition">Reprint</button>
        </div>
    </div>
</div>

<script>
    // Open/close modal
    const openReprintModal = () => {
        document.getElementById("reprintModal").classList.remove("hidden");
        populateTransactions(); // populate dropdown every time modal opens
    };
    const closeReprintModal = () => document.getElementById("reprintModal").classList.add("hidden");

    async function populateTransactions() {
        const select = document.getElementById("regTransId");
        select.innerHTML = '<option value="">Select Transaction</option>';

        try {
            const res = await fetch('../../app/includes/managerModule/getTransactions.php');
            const data = await res.json();

            if (data.error) throw new Error(data.message);

            data.forEach(tx => {
                const option = document.createElement('option');
                const date = tx.date_added.split(' ')[0]; // show only YYYY-MM-DD
                option.value = tx.REG_TRANSACTION_ID;
                option.textContent = `#${tx.REG_TRANSACTION_ID} - ${date} - ${tx.staff_name || 'Unknown'}`;
                select.appendChild(option);
            });
        } catch (err) {
            Swal.fire({
                icon: "error",
                title: "Error",
                text: "Failed to fetch transactions: " + err.message
            });
        }
    }

    // Reprint selected receipt
    function reprintReceipt() {
        const id = document.getElementById("regTransId").value;

        if (!id) {
            return Swal.fire({
                icon: "error",
                title: "Missing ID",
                text: "Please select a Transaction."
            });
        }

        closeReprintModal();

        fetch(`../../app/includes/managerModule/managerSalesManagementReprintReceipt.php?reg_trans_id=${encodeURIComponent(id)}`)
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: "question",
                        title: "Confirm Reprint",
                        text: `Do you want to reprint receipt for Transaction ID #${id}?`,
                        showCancelButton: true,
                        confirmButtonText: "Yes, Print",
                        cancelButtonText: "Cancel",
                        confirmButtonColor: "#f97316",
                        cancelButtonColor: "#6b7280"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            Swal.fire({
                                icon: "success",
                                title: "Printing...",
                                text: "Receipt is being reprinted.",
                                timer: 1200,
                                showConfirmButton: false
                            });
                            window.open(data.url, "_blank");
                        }
                    });
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: data.message || "Transaction not found."
                    });
                }
            })
            .catch(err => Swal.fire({
                icon: "error",
                title: "Network Error",
                text: err.message
            }));
    }
</script>
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
<div id="reprintModal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50">
    <div class="bg-[var(--calc-bg-btn)] rounded-2xl p-5 w-11/12 sm:w-[420px] shadow-2xl border border-[var(--border-color)]">

        <!-- Modal Header -->
        <div class="flex items-center justify-between mb-4 pb-3 border-b border-[var(--border-color)]">
            <h3 class="text-base font-bold text-[var(--text-color)] flex items-center gap-2">
                <i class="fa-solid fa-receipt text-orange-500"></i> Select Transaction
            </h3>
            <button onclick="closeReprintModal()"
                class="w-7 h-7 flex items-center justify-center rounded-full border border-[var(--border-color)]
                       text-[var(--text-color)] hover:bg-red-500 hover:text-white hover:border-red-500
                       transition-all duration-200 text-lg leading-none">
                &times;
            </button>
        </div>

        <!-- Search -->
        <div class="relative mb-3">
            <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-[var(--text-color)] opacity-40 text-xs"></i>
            <input
                id="reprintSearch"
                type="text"
                placeholder="Search by ID or staff name…"
                oninput="filterTransactions()"
                class="w-full pl-8 pr-3 py-2 text-sm rounded-xl border border-[var(--border-color)]
                       bg-[var(--background-color)] text-[var(--text-color)]
                       focus:outline-none focus:ring-2 focus:ring-orange-400 transition" />
        </div>

        <!-- Transaction List -->
        <div id="transactionList"
            class="space-y-1.5 max-h-60 overflow-y-auto pr-1 mb-4"
            style="scrollbar-width: thin; scrollbar-color: var(--border-color) transparent;">
            <p id="transListPlaceholder" class="text-center text-sm opacity-50 py-6 text-[var(--text-color)]">
                Loading transactions…
            </p>
        </div>

        <!-- Hidden value holder -->
        <input type="hidden" id="selectedTransId" value="" />

        <!-- Footer -->
        <div class="flex gap-2 pt-3 border-t border-[var(--border-color)]">
            <button onclick="closeReprintModal()"
                class="flex-1 py-2.5 rounded-xl border border-[var(--border-color)] text-sm font-semibold
                       text-[var(--text-color)] hover:bg-red-500 hover:text-white hover:border-red-500
                       transition-all duration-200">
                Cancel
            </button>
            <button onclick="reprintReceipt()"
                class="flex-1 py-2.5 rounded-xl bg-orange-500 hover:bg-orange-600 text-white
                       text-sm font-semibold transition-all duration-200">
                <i class="fa-solid fa-print mr-1"></i> Reprint
            </button>
        </div>

    </div>
</div>

<script>
    let _allTransactions = [];

    const openReprintModal = () => {
        document.getElementById("reprintModal").classList.remove("hidden");
        document.getElementById("selectedTransId").value = "";
        document.getElementById("reprintSearch").value = "";
        populateTransactions();
    };

    const closeReprintModal = () => {
        document.getElementById("reprintModal").classList.add("hidden");
    };

    async function populateTransactions() {
        const list = document.getElementById("transactionList");
        list.innerHTML = `<p class="text-center text-sm opacity-50 py-6 text-[var(--text-color)]">Loading…</p>`;

        try {
            const res = await fetch('../../app/includes/managerModule/getTransactions.php');
            const data = await res.json();
            if (data.error) throw new Error(data.message);

            _allTransactions = data;
            renderList(data);

        } catch (err) {
            list.innerHTML = `<p class="text-center text-sm text-red-400 py-6">Failed to load: ${err.message}</p>`;
        }
    }

    function renderList(transactions) {
        const list = document.getElementById("transactionList");

        if (!transactions.length) {
            list.innerHTML = `<p class="text-center text-sm opacity-50 py-6 text-[var(--text-color)]">No transactions found.</p>`;
            return;
        }

        list.innerHTML = transactions.map(tx => {
            const dt = new Date(tx.date_added);
            const date = dt.toLocaleDateString('en-CA');
            const hours = String(dt.getHours()).padStart(2, '0');
            const mins = String(dt.getMinutes()).padStart(2, '0');
            const time = `${hours}:${mins}`;
            const total = parseFloat(tx.TOTAL_AMOUNT || 0).toFixed(2);
            const staff = tx.staff_name || 'Unknown';

            return `
            <div
                class="transaction-row flex items-center justify-between gap-3 px-3 py-2.5 rounded-xl
                       border border-transparent cursor-pointer select-none
                       hover:border-orange-400 hover:bg-orange-500/10 transition-all duration-150"
                data-id="${tx.REG_TRANSACTION_ID}"
                onclick="selectTransaction(this, '${tx.REG_TRANSACTION_ID}')">

                <!-- Left: ID badge + staff + datetime -->
                <div class="flex items-center gap-2.5 min-w-0">
                    <span class="shrink-0 w-10 h-10 rounded-lg bg-orange-500/15 text-orange-500
                                 flex items-center justify-center text-xs font-bold">
                        #${tx.REG_TRANSACTION_ID}
                    </span>
                    <div class="min-w-0">
                        <p class="text-sm font-semibold text-[var(--text-color)] truncate">${staff}</p>
                        <p class="text-xs opacity-50 text-[var(--text-color)]">${date} &nbsp;${time}</p>
                    </div>
                </div>

                <!-- Right: Total -->
                <span class="shrink-0 text-sm font-bold text-orange-500">&#8369;${total}</span>
            </div>`;
        }).join('');
    }

    function selectTransaction(el, id) {
        document.querySelectorAll('.transaction-row').forEach(r => {
            r.classList.remove('border-orange-400', 'bg-orange-500/20', 'ring-1', 'ring-orange-400');
        });
        el.classList.add('border-orange-400', 'bg-orange-500/20', 'ring-1', 'ring-orange-400');
        document.getElementById("selectedTransId").value = id;
    }

    function filterTransactions() {
        const q = document.getElementById("reprintSearch").value.toLowerCase().trim();
        if (!q) return renderList(_allTransactions);
        const filtered = _allTransactions.filter(tx =>
            String(tx.REG_TRANSACTION_ID).includes(q) ||
            (tx.staff_name || '').toLowerCase().includes(q)
        );
        renderList(filtered);
    }

    function reprintReceipt() {
        const id = document.getElementById("selectedTransId").value;

        if (!id) {
            return Swal.fire({
                icon: "warning",
                title: "No Transaction Selected",
                text: "Please select a transaction from the list."
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
                        text: `Reprint receipt for Transaction #${id}?`,
                        showCancelButton: true,
                        confirmButtonText: "Yes, Print",
                        cancelButtonText: "Cancel",
                        confirmButtonColor: "#f97316",
                        cancelButtonColor: "#6b7280"
                    }).then(result => {
                        if (result.isConfirmed) {
                            Swal.fire({
                                icon: "success",
                                title: "Printing…",
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
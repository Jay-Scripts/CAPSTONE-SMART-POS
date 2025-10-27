document.addEventListener("click", async (e) => {
  if (e.target.classList.contains("serve-btn")) {
    const regId = e.target.dataset.id;

    const confirm = await Swal.fire({
      title: "Mark as 'Now Serving'?",
      text: `Transaction #${regId}`,
      icon: "question",
      showCancelButton: true,
      confirmButtonColor: "#16a34a",
      cancelButtonColor: "#d33",
      confirmButtonText: "Yes, serve it",
    });

    if (confirm.isConfirmed) {
      const res = await fetch(
        "../../app/includes/BVS/BVSserveTransaction.php",
        {
          method: "POST",
          headers: {
            "Content-Type": "application/x-www-form-urlencoded",
          },
          body: `reg_id=${regId}`,
        }
      );

      const result = await res.json();

      if (result.success) {
        Swal.fire({
          icon: "success",
          title: "Now Serving!",
          text: `Transaction #${regId} is now serving.`,
          timer: 1200,
          showConfirmButton: false,
        }).then(() => {
          // 🧾 Fetch and print pick slip (like kiosk)
          fetch(`../../app/includes/BVS/BVSgeneratePickSlip.php?id=${regId}`)
            .then((res) => res.text())
            .then((receiptHTML) => {
              const printWindow = window.open(
                "",
                "_blank",
                "width=400,height=600"
              );
              printWindow.document.write(receiptHTML);
              printWindow.document.close();
              printWindow.focus();
              printWindow.print();
            });

          // 🔄 Refresh transactions list
          fetchTransactions();
        });
      } else {
        Swal.fire("Error", "Failed to update transaction.", "error");
      }
    }
  }
});

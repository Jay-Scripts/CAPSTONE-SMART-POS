document.getElementById("wasteForm").addEventListener("submit", async (e) => {
  e.preventDefault();

  const formData = new FormData(e.target);

  try {
    const res = await fetch(
      "../../app/includes/managerModule/managerLogwaste.php",
      {
        method: "POST",
        body: formData,
      }
    );

    const data = await res.json();

    if (data.status === "success") {
      Swal.fire({
        icon: "success",
        title: "Waste Logged",
        text: data.message,
        confirmButtonColor: "#3085d6",
      });
      e.target.reset();
    } else {
      Swal.fire({
        icon: "error",
        title: "Error",
        text: data.message,
        confirmButtonColor: "#d33",
      });
    }
  } catch (err) {
    Swal.fire({
      icon: "error",
      title: "Server Error",
      text: "Error connecting to server.",
      confirmButtonColor: "#d33",
    });
  }
});

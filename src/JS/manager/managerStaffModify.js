const modifySpinner = document.getElementById("modifyLoadingSpinner");
const modifySubmit = document.getElementById("modifySubmitBtn");

modifySubmit.addEventListener("submit", (e) => {
  modifySpinner.classList.remove("hidden");
  modifySubmit.disabled = true;

  setTimeout(() => {
    modifySpinner.classList.add("hidden");
    modifySubmit.disabled = false;
  }, 2000);
});

function toggleModal(id) {
  const modal = document.getElementById(id);
  modal.classList.toggle("hidden");
  modal.classList.toggle("flex");
}
window.onload = function () {
  showModule("milktea");
};

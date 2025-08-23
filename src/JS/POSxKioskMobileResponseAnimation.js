// //disabled the default context of chrome like save image etc, for test the longpress in mobile dev
// document.addEventListener("contextmenu", function (e) {
//   e.preventDefault();
// });

// document.querySelectorAll(".actionBtn").forEach((btn) => {
//   const span = btn.querySelector(".actionText");
//   const icon = btn.querySelector(".icon");

//   let timers;

//   const addHoverEffect = () => {
//     span.classList.add("translate-x-[200%]", "opacity-0");
//     icon.classList.add("opacity-100", "w-full");
//   };

//   const removeHoverEffect = () => {
//     span.classList.remove("translate-x-[200%]", "opacity-0");
//     icon.classList.remove("opacity-100", "w-full");
//   };

//   //desktop function seuodo
//   btn.addEventListener("mouseenter", addHoverEffect);
//   btn.addEventListener("mouseleave", removeHoverEffect);

//   //mobile seudo
//   btn.addEventListener("touchstart", (e) => {
//     timers = setTimeout(() => {
//       addHoverEffect();
//     }, 200);
//   });

//   const clearHoverEffect = () => {
//     clearTimeout(timers);
//     removeHoverEffect();
//   };

//   btn.addEventListener("touchend", clearHoverEffect);
//   btn.addEventListener("touchcancel", clearHoverEffect);
//   btn.addEventListener("touchmove", clearHoverEffect);
// });

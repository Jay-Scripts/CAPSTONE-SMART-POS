// setInterval(() => {
//   fetch("../../app/config/dbConnection.php")
//     .then((res) => res.text())
//     .then((data) => {
//       if (!data.includes("Connected")) {
//         window.location.href = "connectionLost.php";
//       }
//     })
//     .catch(() => {
//       // In case fetch itself fails (server completely down)
//       window.location.href = "connectionLost.php";
//     });
// }, 500);

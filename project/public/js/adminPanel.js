// document.getElementById("edit").addEventListener("click", function(){
//     document.querySelector(".card-inner").removeAttribute("hidden");
// });

function myFunction() {
    var x = document.querySelector(".card-inner");
    if (x.style.display === "none") {
      x.style.display = "block";
    } else {
      x.style.display = "none";
    }
  }

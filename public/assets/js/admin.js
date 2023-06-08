"use strict";
// Fermeture des messages flashs
const closeMessages = document.querySelectorAll(".alertClose");
closeMessages.forEach((element) => {
  element.addEventListener("click", function () {
    console.log("click fermerture message");
    element.parentNode.style.display = "none";
  });
});

// Je crée un lien depuis l'admin vers le site
const linkSite = document.querySelector(".header_logo");
linkSite.addEventListener("click", () => {
  console.log("click");
  document.location.href = "/";
});

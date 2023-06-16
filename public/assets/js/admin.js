"use strict";
// Fermeture des messages flashs
// const closeMessages = document.querySelectorAll(".alertClose");
// closeMessages.forEach((element) => {
//   element.addEventListener("click", function () {
//     console.log("click fermerture message");
//     element.parentNode.style.display = "none";
//   });
// });

// Je crée un lien depuis l'admin vers le site
const linkSite = document.querySelector(".header_logo");
linkSite.addEventListener("click", () => {
  console.log("click");
  document.location.href = "/";
});

// Animation du menu admin
const subMenuParameters = document.querySelector("ul.submenu.parameter_items");
const subMenuTools = document.querySelector("ul.submenu.tools_items");
const itemParameters = document.querySelector(".menu_item.parameters");
const itemTools = document.querySelector(".menu_item.tools");

itemParameters.addEventListener("click", () => {
  subMenuParameters.classList.toggle("submenu_open");
});

itemTools.addEventListener("click", () => {
  subMenuTools.classList.toggle("submenu_open");
});

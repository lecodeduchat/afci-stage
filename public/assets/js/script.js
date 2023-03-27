"use strict";
console.log("coucou");
const btnMainMenu = document.querySelector(".main_menu-btn");
const mainMenu = document.querySelector(".main_menu-list");

btnMainMenu.addEventListener("click", function () {
  console.log("click");
  mainMenu.classList.toggle("main_menu-list--open");
});
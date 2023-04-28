"use strict";
// Rendez-vous étape 1 : choix du type de séance ----------------------------
// Sauvegarde du choix du type de séance dans le local storage
const careChoices = document.querySelectorAll(".care_choice");
const careItems = document.querySelectorAll(".care_item");

careChoices.forEach((careChoice) => {
  careChoice.addEventListener("click", function () {
    let careId = careChoice.getAttribute("data-set");
    localStorage.setItem("careId", careId);
  });
});

// Ouverture des onglets pour le choix du type de séance --------------------
const firstCare = document.querySelector(".firstCare");
const firstCareList = document.querySelector(".firstCare_list");
const firstCareChevron = document.querySelector(".firstCareChevron");
const secondCare = document.querySelector(".secondCare");
const secondCareList = document.querySelector(".secondCare_list");
const secondCareChevron = document.querySelector(".secondCareChevron");

if (firstCare !== null) {
  firstCare.addEventListener("click", function () {
    firstCareList.classList.toggle("firstCare_list--open");
    firstCareChevron.classList.toggle("fa-chevron-down");
    firstCareChevron.classList.toggle("fa-chevron-right");
  });
}
if (secondCare !== null) {
  secondCare.addEventListener("click", function () {
    secondCareList.classList.toggle("secondCare_list--open");
    secondCareChevron.classList.toggle("fa-chevron-down");
    secondCareChevron.classList.toggle("fa-chevron-right");
  });
}

"use strict";
// Sauvegarde des données dans le local storage -----------------------------
// Sauvegarde du choix du type de séance
const careChoices = document.querySelectorAll(".care_choice");
const careItems = document.querySelectorAll(".care_item");

careChoices.forEach((careChoice) => {
  careChoice.addEventListener("click", function () {
    let careId = careChoice.getAttribute("data-set");
    localStorage.setItem("careId", careId);
  });
});
// Sauvegarde du choix du créneau horaire
const slotsTimes = document.querySelectorAll(".slots_time");
slotsTimes.forEach((slotsTime) => {
  slotsTime.addEventListener("click", function () {
    let time = slotsTime.getAttribute("data-set");
    localStorage.setItem("time", time);
  });
});
// Je vérifie si le choix du type de séance n'a pas été modifié
const selectCares = document.querySelector("#cares");
if (selectCares !== null) {
  selectCares.addEventListener("change", function () {
    localStorage.setItem("careId", selectCares.value);
  });
}

// Ouverture des onglets de la page "Rendez-vous" ---------------------------
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

// Ouverture des jours pour faire apparaître les horaires disponibles -------------------
const days = document.querySelectorAll(".slots_date");

days.forEach((day) => {
  day.addEventListener("click", function () {
    const dayChevron = day.querySelector(".day-chevron");
    const slots = day.querySelector(".slots_times");
    slots.classList.toggle("slots_times--open");
    dayChevron.classList.toggle("fa-chevron-down");
    dayChevron.classList.toggle("fa-chevron-right");
  });
});

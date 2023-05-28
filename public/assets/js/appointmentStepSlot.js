"use strict";

// Je passe à selected le type de séance choisi ----------------------
const selectCares = document.querySelector("#cares");
let careId = localStorage.getItem("careId");

// Je vérifie si le choix du type de séance n'a pas été modifié ------
if (selectCares) {
  const options = selectCares.querySelectorAll("option");
  options.forEach((option) => {
    if (option.value == careId) {
      option.setAttribute("selected", "selected");
    }
  });
  selectCares.addEventListener("change", function () {
    localStorage.setItem("careId", selectCares.value);
  });
}

// Sauvegarde du choix du créneau horaire ----------------------------
const slotsTimes = document.querySelectorAll(".slots_time");

slotsTimes.forEach((slotsTime) => {
  slotsTime.addEventListener("click", function () {
    let time = slotsTime.getAttribute("data-time");
    let date = slotsTime.getAttribute("data-date");
    let nameDay = slotsTime.getAttribute("data-nameDay");
    localStorage.setItem("time", time);
    localStorage.setItem("date", date);
    localStorage.setItem("nameDay", nameDay);
    let careId = localStorage.getItem("careId");
    if (careId == 2 || careId == 5) {
      console.log("RDV enfant");
      slotsTime.setAttribute("href", "/rendez-vous/enfants");
    } else {
      console.log("RDV adulte");
      slotsTime.setAttribute("href", "/rendez-vous/nouveau");
    }
  });
});

// Ouverture des jours pour faire apparaître les horaires disponibles --
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

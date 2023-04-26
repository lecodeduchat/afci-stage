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
      slotsTime.setAttribute("href", "/rendez-vous/new");
    }
  });
});

// Je passe à selected le type de séance choisi
const selectCares = document.querySelector("#cares");
let careId = localStorage.getItem("careId");

// Je vérifie si le choix du type de séance n'a pas été modifié
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
// Sauvegarde du choix de l'enfant --------------------------------------------
const childsSelect = document.querySelector("#childs");
if (childsSelect) {
  let childId = childsSelect.value;
  localStorage.setItem("childId", childId);
  childsSelect.addEventListener("change", function () {
    localStorage.setItem("childId", childsSelect.value);
  });
}
// Remplissage des champs du formulaire de rendez-vous et de l'affichage de la réservation--------------------
const reservation = document.querySelector(".reservation");
if (reservation) {
  const dateDiv = document.querySelector(".appointment_date");
  const timeDiv = document.querySelector(".appointment_time");
  const careName = document.querySelector(".reservation_infos_care");
  const careDuration = document.querySelector(".reservation_infos_duration");
  const carePrice = document.querySelector(".appointment_price");
  const months = [
    "Janvier",
    "Février",
    "Mars",
    "Avril",
    "Mai",
    "Juin",
    "Juillet",
    "Août",
    "Septembre",
    "Octobre",
    "Novembre",
    "Décembre",
  ];
  // Je récupère les données du local storage
  let time = localStorage.getItem("time");
  let date = localStorage.getItem("date");
  let nameDay = localStorage.getItem("nameDay");
  let day = date.slice(8, 10);
  let month = date.slice(5, 7);
  let monthName = months[month - 1];
  let year = date.slice(0, 4);
  let hours = time.slice(0, 2);
  let minutes = time.slice(3, 5);
  // Mettre la première lettre en majuscule
  nameDay = capitalizeFirstLetter(nameDay);
  // Affichage de la date et de l'heure du rendez-vous
  dateDiv.textContent = `${nameDay} ${day} ${monthName} ${year}`;
  timeDiv.textContent = `${hours}h${minutes}`;

  // Affichage du type de séance
  const selectAppointmentsCare = document.querySelector("#appointments_care");
  if (selectAppointmentsCare) {
    const options = selectAppointmentsCare.querySelectorAll("option");
    careId = localStorage.getItem("careId");
    options.forEach((option) => {
      if (option.value == careId) {
        option.setAttribute("selected", "selected");
      }
    });
  }

  // Je récupère les données de la table "care" -------------------------------
  const cares = document.querySelector("table.cares");
  const tbody = cares.querySelector("tbody");
  const rows = tbody.querySelectorAll("tr");
  let dataCares = [];
  let cpt = 0;
  rows.forEach((row) => {
    const items = row.querySelectorAll("td");
    dataCares[cpt] = [];
    items.forEach((item) => {
      dataCares[cpt].push(item.textContent);
    });
    cpt++;
  });

  // Affichage des informations de la séance
  dataCares.forEach((dataCare) => {
    if (dataCare[0] == careId) {
      careName.textContent = dataCare[1];
      careDuration.textContent = dataCare[2].slice(3, 5) + " minutes";
      carePrice.textContent = dataCare[3] + ",00";
    }
  });
  // Injection des données dans le formulaire de rendez-vous --------------------
  const form = document.querySelector(".appointment_form");
  if (form) {
    const selectHour = document.querySelector("#appointments_time_hour");
    const selectMinute = document.querySelector("#appointments_time_minute");
    const selectDay = document.querySelector("#appointments_date_day");
    const selectMonth = document.querySelector("#appointments_date_month");
    const selectYear = document.querySelector("#appointments_date_year");
    const btnSubmit = document.querySelector(".btn_appointment");

    const optionsHour = selectHour.querySelectorAll("option");
    const optionsMinute = selectMinute.querySelectorAll("option");
    const optionsDay = selectDay.querySelectorAll("option");
    const optionsMonth = selectMonth.querySelectorAll("option");
    const optionsYear = selectYear.querySelectorAll("option");

    optionsHour.forEach((option) => {
      if (option.value == hours) {
        option.setAttribute("selected", "selected");
      }
    });
    optionsMinute.forEach((option) => {
      if (option.value == minutes) {
        option.setAttribute("selected", "selected");
      }
    });
    optionsDay.forEach((option) => {
      if (option.value == day) {
        option.setAttribute("selected", "selected");
      }
    });

    optionsMonth.forEach((option) => {
      // Si le mois est inférieur à 10, je supprime le 0
      month = month.slice(0, 1) == "0" ? month.slice(1, 2) : month;
      if (option.value == month) {
        option.setAttribute("selected", "selected");
      }
    });
    optionsYear.forEach((option) => {
      if (option.value == year) {
        option.setAttribute("selected", "selected");
      }
    });
    let childId = localStorage.getItem("childId");
    const inputChildId = document.querySelector("#appointments_child_id");
    inputChildId.setAttribute("value", childId);
    console.log(inputChildId.value);
    btnSubmit.addEventListener("click", function () {
      // Je vide les données du local storage après la validation du formulaire
      localStorage.setItem("time", "");
      localStorage.setItem("date", "");
      localStorage.setItem("nameDay", "");
      localStorage.setItem("careId", "");
      localStorage.setItem("childId", "");
    });
  }
}

function capitalizeFirstLetter(string) {
  return string.charAt(0).toUpperCase() + string.slice(1);
}

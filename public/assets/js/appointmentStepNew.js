"use strict";

// Injection des données dans le formulaire de rendez-vous --------------------
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

// Je corrige la valeur hours si elle est inférieure à 10 car 09 n'est pas une value valide dans un select!!!
if (hours == "09") {
  hours = 9;
}
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

btnSubmit.addEventListener("click", function () {
  // Je vide les données du local storage après la validation du formulaire
  localStorage.setItem("time", "");
  localStorage.setItem("date", "");
  localStorage.setItem("nameDay", "");
  localStorage.setItem("careId", "");
  localStorage.setItem("childId", "");
});

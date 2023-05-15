"use strict";
const appointmentDate = document.querySelector("#dates");
const appointmentTime = document.querySelector("#times");
let appointmentDateValue, appointmentTimeValue;
// PRISE DE RENDEZ-VOUS
// J'injecte la date et l'heure dans le formulaire non affiché
let date = localStorage.getItem("date");
let time = localStorage.getItem("time");
console.log(time.slice(0, 5));
if (date && time) {
  document.querySelector("#admin_appointments_date").value = date + " " + time;
}
// J'injecte la date et l'heure dans le pseudo formulaire affiché
let appointmentDateOptions = document.querySelectorAll("#dates option");
let appointmentTimeOptions = document.querySelectorAll("#times option");
appointmentDateOptions.forEach((option) => {
  if (option.value == date) {
    option.setAttribute("selected", "selected");
    getSlots();
  }
});

// En fonction de la date choisie, on affiche les créneaux disponibles
function getSlots() {
  appointmentDateValue = appointmentDate.value;
  for (let i = 0; i < slots.length; i++) {
    if (slots[i].date == appointmentDateValue) {
      slots[i].slots.forEach((time) => {
        appointmentTime.innerHTML += `<option value="${time}">${time}</option>`;
      });
    }
  }
  appointmentTimeOptions.forEach((option) => {
    console.log("optionValue: ", option.value, "time: ", time.slice(0, 5));
    if (option.value == time.slice(0, 5)) {
      option.setAttribute("selected", "selected");
    }
  });
}

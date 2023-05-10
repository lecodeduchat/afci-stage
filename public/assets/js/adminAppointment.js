"use strict";
const appointmentDate = document.querySelector("#dates");
const appointmentTime = document.querySelector("#times");
let appointmentDateValue, appointmentTimeValue;

// En fonction de la date choisie, on affiche les créneaux disponibles
appointmentDate.addEventListener("change", () => {
  appointmentDateValue = appointmentDate.value;
  for (let i = 0; i < slots.length; i++) {
    if (slots[i].date == appointmentDateValue) {
      slots[i].slots.forEach((time) => {
        appointmentTime.innerHTML += `<option value="${time}">${time}</option>`;
      });
    }
  }
});

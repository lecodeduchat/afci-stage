"use strict";
const modal = document.querySelector(".modal");
const appointmentDate = document.querySelector("#dates");
const appointmentTime = document.querySelector("#times");
const buttonsView = document.querySelectorAll(".fc-button-group button");
let appointmentDateValue, appointmentTimeValue, xStart, rows, columns;
let days = [];
let cpt = 0;

// En fonction de la date choisie, on affiche les créneaux disponibles
if (appointmentDate) {
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
}
// Je mets un écouteur d'évènement sur chaque bouton de vue du calendrier
buttonsView.forEach((button) => {
  button.addEventListener("click", () => {
    // Je récupère les lignes du calendrier
    rows = document.querySelectorAll(".fc-timegrid-slot-lane");
    // Je récupère les colonnes du calendrier
    columns = document.querySelectorAll(".fc-timegrid-col");
    // Je récupère la largeur de la première colonne et j'ajoute 18px de marge pour avoir le début de la première case
    if (columns[0]) {
      xStart = columns[0].offsetWidth + 18;
    }
    columns.forEach((column) => {
      let date = column.getAttribute("data-date");
      days[cpt] = {
        date: date,
        xStart: xStart,
        xEnd: xStart + column.offsetWidth,
      };
      cpt++;
      xStart += column.offsetWidth;
      if (date == "2023-05-12") {
        column.style.backgroundColor = "lightblue";
      }
    });
    console.log(days);
    // Je place un écouteur d'évènement sur chaque case du calendrier
    rows.forEach((row) => {
      row.addEventListener("click", (e) => {
        console.log("test", e.clientX);
        let time = row.getAttribute("data-time");
        let x = e.clientX;
        days.forEach((day) => {
          if (x >= day.xStart && x <= day.xEnd) {
            console.log(day.date, time);
            localStorage.setItem("date", day.date);
            localStorage.setItem("time", time);
          }
        });
        modal.style.display = "block";
      });
      let time = row.getAttribute("data-time");
      if (time == "08:30:00") {
        row.style.backgroundColor = "white";
      }
    });
  });
});

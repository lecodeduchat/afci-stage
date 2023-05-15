"use strict";
// Animation du menu en mode mobile
const menuCalendarMobile = document.querySelector(".menu-calendar-mobile");
const itemsMenu = document.querySelector(".fc-toolbar-chunk:nth-of-type(3)");
let buttonsView, xStart, rows, columns;
let days = [];
let cpt = 0;

// Je mets un écouteur d'évènement sur le bouton du menu en mode mobile
menuCalendarMobile.addEventListener("click", function () {
  itemsMenu.classList.toggle("open-menu-calendar-mobile");
  buttonsView = document.querySelectorAll(".fc-button-group button");
  // Je mets un écouteur d'évènement sur chaque bouton de vue du calendrier
  buttonsView.forEach((button) => {
    button.addEventListener("click", () => {
      getDateTime();
    });
  });
});

// Je lance la fonction pour récupérer les dates et heures du calendrier si on est en mode desktop
if (window.innerWidth > 768) {
  getDateTime();
}

function getDateTime() {
  // Je récupère les lignes du calendrier
  rows = document.querySelectorAll(".fc-timegrid-slot-lane");
  //! Je récupère les colonnes du calendrier correspondant aux jours (les colonnes des heures sont exclues)
  columns = document.querySelectorAll(".fc-timegrid-col.fc-day");
  // Je récupère la largeur de la première colonne et j'ajoute 8px de marge pour avoir le début de la première case
  if (columns[0]) {
    xStart = columns[0].offsetWidth + 8;
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
      // modal.style.display = "block";
      document.location.href = "/admin/rendez-vous/nouveau";
    });
    let time = row.getAttribute("data-time");
    if (time == "08:30:00") {
      row.style.backgroundColor = "white";
    }
  });
}

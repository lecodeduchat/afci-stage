"use strict";
// Fermeture des messages flashs
const closeMessages = document.querySelectorAll(".alertClose");
closeMessages.forEach((element) => {
  element.addEventListener("click", function () {
    console.log("click fermerture message");
    element.parentNode.style.display = "none";
  });
});
// Animation du menu en mode mobile
const menuCalendarMobile = document.querySelector(".menu-calendar-mobile");
const itemsMenu = document.querySelector(".fc-toolbar-chunk:nth-of-type(3)");
let buttonsView, xStart, rows, columns, columnHours;
let days = [];
let cpt = 0;
// Valeur de la marge (margin-left) du calendar sur mobile
let marge = 8;

// Je mets un écouteur d'évènement sur le bouton du menu en mode mobile
menuCalendarMobile.addEventListener("click", function () {
  itemsMenu.classList.toggle("open-menu-calendar-mobile");
  buttonsView = document.querySelectorAll(".fc-button-group button");
  // Je mets un écouteur d'évènement sur chaque bouton de vue du calendrier
  buttonsView.forEach((button) => {
    button.addEventListener("click", () => {
      getDateTime(marge);
    });
  });
});

// Je lance la fonction pour récupérer les dates et heures du calendrier si on est en mode desktop
if (window.innerWidth > 768) {
  const menu = document.querySelector(".menu_container");
  marge = menu.offsetWidth + 18;
  console.log("marge", marge);
  getDateTime(marge);
}
/**
 * Fonction qui récupère les dates et heures du calendrier
 */
function getDateTime(marge) {
  // Je récupère les lignes du calendrier
  rows = document.querySelectorAll(".fc-timegrid-slot-lane");
  //! Je récupère les colonnes du calendrier correspondant aux jours (les colonnes des heures sont exclues)
  columnHours = document.querySelector(".fc-timegrid-col");
  columns = document.querySelectorAll(".fc-timegrid-col.fc-day");
  // Je récupère la largeur de la première colonne et j'ajoute 8px de marge pour avoir le début de la première case
  if (columnHours) {
    xStart = columnHours.offsetWidth + marge;
    console.log(
      "xStart",
      xStart,
      "columnHours.offsetWidth",
      columnHours.offsetWidth,
      "marge",
      marge
    );
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
  });
  console.log(days);
  // Je place un écouteur d'évènement sur chaque case du calendrier
  rows.forEach((row) => {
    row.addEventListener("click", (e) => {
      let time = row.getAttribute("data-time");
      let x = e.clientX;
      console.log("test", e.clientX, "time", time);
      days.forEach((day) => {
        console.log("day", day.xStart, day.xEnd);
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

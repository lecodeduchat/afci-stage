"use strict";
/**
 * Fonction qui permet de mettre la première lettre d'une chaine de caractère en majuscule
 * @param {*} string
 * @returns
 */
function capitalizeFirstLetter(string) {
  return string.charAt(0).toUpperCase() + string.slice(1);
}
// Affichage des informations de la réservation --------------------
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
// Je récupère les données du local storage ------------------------
let time = localStorage.getItem("time");
let date = localStorage.getItem("date");
let nameDay = localStorage.getItem("nameDay");
let careId = localStorage.getItem("careId");
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

// Affichage des informations de la séance ----------------------------------
let firstnameChild = "";
const lastStep = document.querySelector(".last-step");
// Pour savoir si je suis sur la dernière étape de la pris de rendez-vous
if (lastStep) {
  console.log("test");
  firstnameChild = firstnameLastChild;
}
dataCares.forEach((dataCare) => {
  if (dataCare[0] == careId) {
    console.log(careId);
    if (careId == 2 || careId == 5) {
      let childId = localStorage.getItem("childId");
      if (typeof childId !== "undefined" && childId == "newChild") {
        console.log(firstnameChild);
        careName.textContent = dataCare[1] + " pour " + firstnameChild;
      } else {
        careName.textContent =
          dataCare[1] + " pour " + childs[childId].firstname;
      }
    } else {
      careName.textContent = dataCare[1];
    }
    careDuration.textContent = dataCare[2].slice(3, 5) + " minutes";
    carePrice.textContent = dataCare[3] + ",00";
  }
});

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

// Affichage des informations de la séance ----------------------------------
const lastStep = document.querySelector(".last-step");

// Je récupère l'url de la page
let pathname = window.location.pathname;

for (let key in dataCares) {
  if (dataCares[key].id == careId) {
    if (careId == 2 || (careId == 5 && pathname == "/rendez-vous/nouveau")) {
      // Je récupère le prénom de l'enfant lorsque je suis sur la page /rendez-vous/nouveau
      let childId = localStorage.getItem("childId");
      if (childId == "newChild") {
        childId = dataChilds.pop();
        careName.textContent =
          dataCares[key].name + " pour " + dataChilds[childId].firstname;
      } else {
        careName.textContent =
          dataCares[key].name + " pour " + dataChilds[childId].firstname;
      }
    } else {
      careName.textContent = dataCares[key].name;
    }
    let duration = dataCares[key].duration;
    duration = duration.slice(3, 5);
    careDuration.textContent = `${duration} minutes`;
    carePrice.textContent = dataCares[key].price + ",00";
  }
}

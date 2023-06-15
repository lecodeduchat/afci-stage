"use strict";
// J'affiche la carte de réservation sur la page de connexion seulement si je viens du parcours de prise de rendez-vous
// De même pour le login message concernant la réservation
// Je récupère l'url de la page
let pathname = window.location.pathname;
let testParcours =
  pathname == "/connexion" && localStorage.getItem("date") ? true : false;
if (testParcours) {
  const reservationCard = document.querySelector(".reservation");
  const loginMessage = document.querySelector(".login_message");
  reservationCard.classList.remove("hidden");
  loginMessage.classList.remove("hidden");
}

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
let day, month, monthName, year, hours, minutes;
if (date != null) {
  day = date.slice(8, 10);
  month = date.slice(5, 7);
  monthName = months[month - 1];
  year = date.slice(0, 4);
  hours = time.slice(0, 2);
  minutes = time.slice(3, 5);
}
// Mettre la première lettre en majuscule
if (nameDay != null) {
  nameDay = capitalizeFirstLetter(nameDay);
}

// Affichage de la date et de l'heure du rendez-vous
dateDiv.textContent = `${nameDay} ${day} ${monthName} ${year}`;
timeDiv.textContent = `${hours}h${minutes}`;

// Affichage des informations de la séance ----------------------------------
const lastStep = document.querySelector(".last-step");

for (let key in dataCares) {
  if (dataCares[key].id == careId) {
    if ((careId == 2 || careId == 5) && pathname == "/rendez-vous/nouveau") {
      // Je récupère le prénom de l'enfant lorsque je suis sur la page /rendez-vous/nouveau
      let childId = localStorage.getItem("childId");
      console.log(childId);
      if (childId == "newChild") {
        // Je récupère le dernier enfant enregistré
        let child = dataChilds.pop();
        // Je le réinjecte dans le tableau pour pouvoir l'utiliser dans la suite du programme
        dataChilds.push(child);
        careName.textContent = dataCares[key].name + " pour " + child.firstname;
      } else {
        // Je récupère le prénom de l'enfant dans le tableau dataChilds
        for (let child in dataChilds) {
          if (dataChilds[child].id == childId) {
            careName.textContent =
              dataCares[key].name + " pour " + dataChilds[child].firstname;
          }
        }
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
// Le fait d'avoir une date dans le local storage signifie que je viens du parcours de prise de rendez-vous
// Je modifie donc le texte du bouton de connexion
if (pathname == "/connexion" && localStorage.getItem("date")) {
  console.log("test modif bouton login");
  const loginButton = document.querySelector(".btn-login");
  loginButton.textContent = "Se connecter et finaliser votre rendez-vous";
  loginButton.style.width = "290px";
  loginButton.style.height = "100px";
}

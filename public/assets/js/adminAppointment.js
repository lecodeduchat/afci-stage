"use strict";
const appointmentDate = document.querySelector("#dates");
const appointmentTime = document.querySelector("#times");
let appointmentDateValue, appointmentTimeValue;
// PRISE DE RENDEZ-VOUS
// J'injecte la date et l'heure dans le formulaire non affiché
let date = localStorage.getItem("date");
let time = localStorage.getItem("time");
console.log(time.slice(0, 5));
updateDateTime();
// J'injecte la date et l'heure dans le pseudo formulaire affiché
let appointmentDateOptions = document.querySelectorAll("#dates option");
let appointmentTimeOptions;
appointmentDateOptions.forEach((option) => {
  if (option.value == date) {
    option.setAttribute("selected", "selected");
    getSlots();
  }
});

/**
 * Fonction qui récupère les créneaux disponibles pour la date sélectionnée
 */
function getSlots() {
  appointmentDateValue = appointmentDate.value;
  for (let i = 0; i < slots.length; i++) {
    if (slots[i].date == appointmentDateValue) {
      slots[i].slots.forEach((time) => {
        appointmentTime.innerHTML += `<option value="${time}">${time}</option>`;
      });
    }
  }
  appointmentTimeOptions = document.querySelectorAll("#times option");
  appointmentTimeOptions.forEach((option) => {
    if (option.value == time.slice(0, 5)) {
      option.setAttribute("selected", "selected");
    }
  });
}
// J'écoute le changement de date et je mets à jour les données du formulaire
appointmentDate.addEventListener("change", () => {
  appointmentTime.innerHTML = "";
  getSlots();
  date = appointmentDate.value;
  updateDateTime();
});
// J'écoute le changement d'heure et je mets à jour les données du formulaire
appointmentTime.addEventListener("change", () => {
  time = appointmentTime.value;
  updateDateTime();
});
/**
 * Fonction qui met à jour les données du formulaire
 */
function updateDateTime() {
  if (date && time) {
    document.querySelector("#admin_appointments_date").value =
      date + " " + time;
  }
}
// J'écoute le choix de la séance
const cares = document.querySelector("#cares");
cares.addEventListener("change", () => {
  document.querySelector("#admin_appointments_care").value = cares.value;
});

// Recherche d'un patient
const searchPatient = document.querySelector("#search_user");
const searchResults = document.querySelector(".search_results");
let search;
let list_users = [];

searchPatient.addEventListener("keyup", () => {
  // On vide la liste des patients à chaque nouvelle entrée
  searchResults.innerHTML = "";
  list_users = [];
  search = searchPatient.value;
  console.log("search: ", search);
  clients.forEach((client) => {
    if (client.lastname.toLowerCase().includes(search.toLowerCase())) {
      console.log("test2", client.lastname.toLowerCase(), search.toLowerCase());
      list_users.push(client);
    }
  });
  createListUsers();
  selectUser();
});
function createListUsers() {
  list_users.forEach((user) => {
    searchResults.innerHTML += `<li>${user.lastname} ${user.firstname}</li>`;
  });
}
function selectUser() {
  // J'écoute la liste des patients issue de la recherche
  const listUsers = document.querySelectorAll(".search_results li");
  // Je vérifie qu'il y a des patients dans la liste
  if (listUsers.length > 0) {
    listUsers.forEach((user) => {
      user.addEventListener("click", () => {
        console.log("user: ", user.innerHTML);
        searchPatient.value = user.innerHTML;
        searchResults.innerHTML = "";
        // J'injecte l'id du patient dans le formulaire
        clients.forEach((client) => {
          if (client.lastname + " " + client.firstname == searchPatient.value) {
            document.querySelector("#admin_appointments_user_id").value =
              client.id;
          }
        });
      });
    });
  }
}

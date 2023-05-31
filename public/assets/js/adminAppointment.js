"use strict";

// Initialisation des variables ---------------------------------------------
const appointmentDate = document.querySelector("#dates");
const appointmentTime = document.querySelector("#times");
const formUpdateUser = document.querySelector("form[name='admin_users_short']");
const emailUser = document.querySelector("#admin_users_short_email");
const firstnameUser = document.querySelector("#admin_users_short_firstname");
const lastnameUser = document.querySelector("#admin_users_short_lastname");
const homephoneUser = document.querySelector("#admin_users_short_home_phone");
const cellphoneUser = document.querySelector("#admin_users_short_cell_phone");
let appointmentDateValue, appointmentTimeValue;

// PRISE DE RENDEZ-VOUS ------------------------------------------------------
// J'injecte la date et l'heure dans le formulaire non affiché
let date = localStorage.getItem("date");
let time = localStorage.getItem("time");
// console.log(time.slice(0, 5));
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
 * Fonction qui met à jour les données du formulaire des champs date et time
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

// Recherche d'un patient -----------------------------------------------------
const searchPatient = document.querySelector("#search_user");
const searchResults = document.querySelector(".search_results");
let search;
let list_users = [];

searchPatient.addEventListener("keyup", () => {
  // On vide la liste des patients à chaque nouvelle entrée
  searchResults.innerHTML = "";
  list_users = [];
  search = searchPatient.value;

  clients.forEach((client) => {
    if (
      client.lastname.toLowerCase().includes(search.toLowerCase()) ||
      client.firstname.toLowerCase().includes(search.toLowerCase())
    ) {
      list_users.push(client);
    }
  });
  createListUsers();
  selectUser();
  // fillFormUser();
});
/**
 * Fonction qui crée la liste des patients
 * @returns void
 */
function createListUsers() {
  list_users.forEach((user) => {
    searchResults.innerHTML += `<li>${user.lastname} ${user.firstname}</li>`;
  });
}
/**
 * Fonction qui sélectionne un patient dans la liste des patients
 * @returns void
 */
function selectUser() {
  // J'écoute la liste des patients issue de la recherche
  const listUsers = document.querySelectorAll(".search_results li");
  // Je vérifie qu'il y a des patients dans la liste
  if (listUsers.length > 0) {
    searchResults.classList.add("search_results--open");
    listUsers.forEach((user) => {
      user.addEventListener("click", () => {
        searchPatient.value = user.innerHTML;
        searchResults.innerHTML = "";
        searchResults.classList.remove("search_results--open");
        // J'injecte l'id du patient dans le formulaire
        clients.forEach((client) => {
          if (client.lastname + " " + client.firstname == searchPatient.value) {
            document.querySelector("#admin_appointments_user_id").value =
              client.id;
            // Je stocke l'id du patient dans le local storage
            console.log("client.id: ", client.id);
            localStorage.setItem("user_id", client.id);
            fillFormUser();
          }
        });
      });
    });
  }
  addChildId();
  fillFormUser();
}
/**
 * Fonction qui ajoute l'id de l'enfant dans le formulaire
 * @returns void
 */
function addChildId() {
  const child = document.querySelector("#admin_appointments_child_id");
  let careId = document.querySelector("#admin_appointments_care").value;
  if (careId != 2 || careId != 4) {
    console.log("child: ", child.value);
    child.value = null;
  }
}

// J'écoute la checkbox new_user pour savoir si le patient est nouveau
const newUser = document.querySelector("#new_user");
const cursorNewUser = document.querySelector(".cursor_new_user");
const cursorCross = cursorNewUser.querySelector(".fa-xmark");
newUser.addEventListener("change", () => {
  cursorNewUser.classList.toggle("checked");
  cursorCross.classList.toggle("checked");
});

/**
 * Fonction qui remplit les champs du formulaire pour un patient existant sélectionné précédemment
 * @returns void
 */
function fillFormUser() {
  const userId = localStorage.getItem("user_id");
  clients.forEach((client) => {
    if (client.id == userId) {
      firstnameUser.value = client.firstname;
      lastnameUser.value = client.lastname;
      emailUser.value = client.email;
      console.log("client.homephone: ", client.homephone);
      homephoneUser.value = formatPhone(client.homephone);
      cellphoneUser.value = formatPhone(client.cellphone);
    }
  });
}

function formatPhone(phone) {
  if (phone.length == 9) {
    phone = "0" + phone;
  } else {
    phone = "Non renseigné";
  }
  let phoneFormated = phone.replace(
    /(\d{2})(\d{2})(\d{2})(\d{2})(\d{2})/,
    "$1 $2 $3 $4 $5"
  );
  return phoneFormated;
}

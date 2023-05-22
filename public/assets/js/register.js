"use strict";
// Vérification de la date de naissance --------------------------------------
const birthday = document.querySelector("#registration_form_birthday");
const errorBirthday = document.querySelector(".error.birthday");
const regexDate = /^(\d{2})\/(\d{2})\/(\d{4})$/;
const regexPhone = /^0[1-9]([-. ]?[0-9]{2}){4}$/;

birthday.addEventListener("blur", function () {
  const date = birthday.value;

  let errors = [];
  errorBirthday.innerHTML = "";

  if (!regexDate.test(date)) {
    errors.push("La date n'est pas valide");
  } else {
    let year = date.substr(6, 4);
    year = parseInt(year);
    if (year < 1900 || year > 2021) {
      errors.push("L'année n'est pas valide");
    }
    let month = date.substr(3, 2);
    month = parseInt(month);
    if (month < 1 || month > 12) {
      errors.push("Le mois n'est pas valide");
    }
    let day = date.substr(0, 2);
    day = parseInt(day);
    if (day < 1 || day > 31) {
      errors.push("Le jour n'est pas valide");
    }
    if (errors != []) {
      errors.forEach((elt) => {
        errorBirthday.innerHTML += `<span>${elt}</span></br>`;
      });
      //   birthday.focus();
    }
  }
  if (errors != []) {
    errors.forEach((elt) => {
      errorBirthday.innerHTML += `<span>${elt}</span></br>`;
    });
    // birthday.focus();
  }
  // Fonctionne mais génère une erreur dans la console !!!
  //   birthday.blur();
});

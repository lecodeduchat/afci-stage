"use strict";
console.log("adminDaysOn.js");
const openMorning = document.querySelector(".open_morning");
const startMorning = openMorning.querySelector("div:nth-child(2)");
const endMorning = openMorning.querySelector("div:nth-child(3)");
const openMorningYes = openMorning.querySelector("#open_morning_yes");
const openMorningNo = openMorning.querySelector("#open_morning_no");

openMorningYes.addEventListener("change", () => {
  toggleOpenMorning();
});
openMorningNo.addEventListener("change", () => {
  toggleOpenMorning();
});
/**
 * Fonction qui affiche ou non les champs de saisie des horaires d'ouverture du matin
 */
function toggleOpenMorning() {
  if (openMorningYes.checked) {
    startMorning.style.display = "block";
    endMorning.style.display = "block";
  } else if (openMorningNo.checked) {
    days_on_start_morning.value = "00:00";
    days_on_end_morning.value = "00:00";
    startMorning.style.display = "none";
    endMorning.style.display = "none";
  }
}
const openAfternoon = document.querySelector(".open_afternoon");
const startAfternoon = openAfternoon.querySelector("div:nth-child(2)");
const endAfternoon = openAfternoon.querySelector("div:nth-child(3)");
const openAfternoonYes = openAfternoon.querySelector("#open_afternoon_yes");
const openAfternoonNo = openAfternoon.querySelector("#open_afternoon_no");

openAfternoonYes.addEventListener("change", () => {
  toggleOpenAfternoon();
});
openAfternoonNo.addEventListener("change", () => {
  toggleOpenAfternoon();
});
/**
 * Fonction qui affiche ou non les champs de saisie des horaires d'ouverture de l'après-midi
 */
function toggleOpenAfternoon() {
  if (openAfternoonYes.checked) {
    startAfternoon.style.display = "block";
    endAfternoon.style.display = "block";
  } else if (openAfternoonNo.checked) {
    days_on_start_afternoon.value = "00:00";
    days_on_end_afternoon.value = "00:00";
    startAfternoon.style.display = "none";
    endAfternoon.style.display = "none";
  }
}

"use strict";
// Sauvegarde du choix de l'enfant --------------------------------------------
const childsSelect = document.querySelector("#childs");
const formChild = document.querySelector(".formChild");
const btnValidateChild = document.querySelector(".choiceChild");

if (childsSelect) {
  let childId = childsSelect.value;
  localStorage.setItem("childId", childId);
  childsSelect.addEventListener("change", function () {
    if (childsSelect.value == "newChild") {
      formChild.style.display = "block";
      btnValidateChild.style.display = "none";
      localStorage.setItem("childId", childsSelect.value);
    } else {
      localStorage.setItem("childId", childsSelect.value);
      formChild.style.display = "none";
      btnValidateChild.style.display = "block";
    }
  });
}

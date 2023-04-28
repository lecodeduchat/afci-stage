"use strict";
// Sauvegarde du choix de l'enfant --------------------------------------------
const childsSelect = document.querySelector("#childs");
if (childsSelect) {
  let childId = childsSelect.value;
  localStorage.setItem("childId", childId);
  childsSelect.addEventListener("change", function () {
    localStorage.setItem("childId", childsSelect.value);
  });
}

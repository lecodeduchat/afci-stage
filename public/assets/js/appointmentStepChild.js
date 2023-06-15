"use strict";
// Sauvegarde du choix de l'enfant --------------------------------------------
const childsSelect = document.querySelector("#childs");
const formChild = document.querySelector(".formChild");
const btnValidateChild = document.querySelector(".choiceChild");

// Dans le cas d'un premier rendez-vous d'enfant si aucun enfant n'est déjà enregistré
// Je dois initialiser childId à newChild car on ne passera pas par le select
let childId = "newChild";
localStorage.setItem("childId", childId);

// Si il y a déjà au moins un enfant, le select s'affiche
if (childsSelect) {
  childId = childsSelect.value;
  console.log("stepChild: ", childId);
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

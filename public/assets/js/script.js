"use strict";
// Test de sauvegarde de données dans le local storage ---------------------
const careChoices = document.querySelectorAll(".careChoice");
const careItems = document.querySelectorAll(".care_item");
localStorage.setItem("test", "test");
// careItems.forEach((careItem) => {
//   careItem.addEventListener("click", function (e) {
//     console.log(careItem);
//   });
// });

careChoices.forEach((careChoice) => {
  careChoice.addEventListener("click", function () {
    console.log("clic");
    $test = careChoice.getAttribute("data-set");
    console.log($test);
    localStorage.setItem("careChoice", $test);
  });
});
if (localStorage.getItem("careChoice") !== null) {
  console.log(localStorage.getItem("careChoice"));
}
console.log(localStorage.getItem("test"));
// Ouverture et fermeture du menu principale ---------------------------------
const btnMainMenu = document.querySelector(".main_menu-btn");
const mainMenu = document.querySelector(".main_menu-list");
const burger = btnMainMenu.querySelector(".fa-bars");

btnMainMenu.addEventListener("click", function () {
  mainMenu.classList.toggle("main_menu-list--open");
  burger.classList.toggle("fa-xmark");
  burger.classList.toggle("fa-bars");
});

// Animation du menu sur tablette et desktop -------------------------------
const header = document.querySelector("header");
const headerContent = document.querySelector(".header_content");
const headerLogo = document.querySelector(".header_logo");
const mainMenuList = document.querySelector(".main_menu-list");
const gradientColorBar = document.querySelector(".gradient-color-bar");
const windowHeight = window.innerHeight;

window.addEventListener("scroll", () => {
  // Position fixe du menu sur mobile
  if (window.scrollY > 80 && window.innerWidth < 768) {
    gradientColorBar.classList.add("gradient-color-bar--fixed-mobile");
  } else {
    gradientColorBar.classList.remove("gradient-color-bar--fixed-mobile");
  }
  // Position fixe du menu sur tablette et desktop
  if (window.scrollY > windowHeight && window.innerWidth > 768) {
    header.classList.add("header--fixed");
    gradientColorBar.classList.add("gradient-color-bar--fixed");
    if (window.innerWidth < 1200) {
      mainMenuList.classList.add("main_menu-list--fixed");
    }
  } else {
    header.classList.remove("header--fixed");
    gradientColorBar.classList.remove("gradient-color-bar--fixed");
    if (window.innerWidth < 1200) {
      mainMenuList.classList.remove("main_menu-list--fixed");
    }
  }
});

// Google Map ---------------------------------------------------------------
let map;
const haubourdin = { lat: 50.611784712322354, lng: 2.975147782208914 };

function initMap() {
  map = new google.maps.Map(document.getElementById("map"), {
    center: haubourdin,
    zoom: 15,
  });

  const contentString =
    '<div id="content_marker">' +
    '<div id="siteNotice">' +
    "</div>" +
    '<h4 id="firstHeading" class="firstHeading">Charlotte vandermersch</h4>' +
    '<div id="bodyContent">' +
    "<p>Horaires:<br> Lundi: 9:30-19h00<br>" +
    "Mardi: 9h30-19h00<br>" +
    "Mercredi: 9h30-19h00<br>" +
    "Jeudi: 9h30-19h00<br>" +
    "Vendredi:9h30-19h00";
  "</p>" + "</div>" + "</div>";

  const infowindow = new google.maps.InfoWindow({
    content: contentString,
    ariaLabel: "Charlotte vandermersch",
  });

  const marker = new google.maps.Marker({
    position: haubourdin,
    map: map,
    title: "Hello Chiro",
  });

  window.initMap = initMap;
  marker.setMap(map);

  marker.addListener("click", () => {
    infowindow.open({
      anchor: marker,
      map,
    });
  });
}

// Ouverture des onglets de la page "Rendez-vous" ---------------------------
const firstCare = document.querySelector(".firstCare");
const firstCareList = document.querySelector(".firstCare_list");
const firstCareChevron = document.querySelector(".firstCareChevron");
const secondCare = document.querySelector(".secondCare");
const secondCareList = document.querySelector(".secondCare_list");
const secondCareChevron = document.querySelector(".secondCareChevron");

// !TODO: déplacer le code dans un fichier à part sinon génère erreur dès la page d'accueil
firstCare.addEventListener("click", function () {
  firstCareList.classList.toggle("firstCare_list--open");
  firstCareChevron.classList.toggle("fa-chevron-down");
  firstCareChevron.classList.toggle("fa-chevron-right");
});
secondCare.addEventListener("click", function () {
  secondCareList.classList.toggle("secondCare_list--open");
  secondCareChevron.classList.toggle("fa-chevron-down");
  secondCareChevron.classList.toggle("fa-chevron-right");
});

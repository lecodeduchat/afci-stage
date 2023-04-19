"use strict";

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

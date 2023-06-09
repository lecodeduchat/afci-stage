"use strict";
// LOADER --------------------------------------------------------------------
// const loader = document.querySelector(".loader");
// window.addEventListener("load", () => {
//   loader.classList.add("fondu-out");
// });

// Ouverture et fermeture du menu principale ---------------------------------
const btnMainMenu = document.querySelector(".main_menu-btn");
const mainMenu = document.querySelector(".main_menu-list");
const burger = btnMainMenu.querySelector(".fa-bars");

btnMainMenu.addEventListener("click", function () {
  mainMenu.classList.toggle("main_menu-list--open");
  burger.classList.toggle("fa-xmark");
  burger.classList.toggle("fa-bars");
});

// Ouverture et fermeture du menu secondaire des utilisateurs connectés ----
const btnMenuUser = document.querySelector(".profile_menu_title");
const menuUser = document.querySelector(".profile_menu_content");
if (menuUser) {
  btnMenuUser.addEventListener("click", function () {
    menuUser.classList.toggle("profile_menu_content--open");
  });
}

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
    '<div id="bodyContent">' +
    '<h5 id="firstHeading" class="firstHeading">Charlotte vandermersch</h5>'+
    "<p>Horaires:<br> Lundi: 9:30-19h00<br>" +
    "Mardi: 9h30-19h00<br>" +
    "Mercredi: 9h30-19h00<br>" +
    "Jeudi: 9h30-19h00<br>" +
    "Vendredi:9h30-19h00<br>"+
    "Samedi:9h30-13h30"
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

// bouton fermeture des message
const closeMessage = document.querySelectorAll(".alertClose");

closeMessage.forEach((element) => {
  element.addEventListener("click", function () {
    element.parentNode.style.display = "none";
  });
});

// Connexion sans avoir choisi de rendez-vous ------------------------------
const reservation = document.querySelector(".reservation");

if (reservation) {
  const loginMessage = document.querySelector(".login_message");
  // Je teste loginMessage pour éviter une erreur si la page n'est pas celle de la connexion
  if (loginMessage) {
    // Je teste si la date du rendez-vous est définie en local storage
    let time = localStorage.getItem("time");
    if (time == "") {
      loginMessage.style.display = "none";
      reservation.style.display = "none";
    } else {
      loginMessage.style.display = "block";
      reservation.style.display = "block";
    }
  }
}
// Je vide le local storage quand je suis sur la page d'accueil
// Cela évite de garder en mémoire la date d'un rendez-vous qui n'a pas été pris
if (window.location.pathname == "/") {
  localStorage.setItem("time", "");
  localStorage.setItem("date", "");
  localStorage.setItem("nameDay", "");
  localStorage.setItem("careId", "");
  localStorage.setItem("childId", "");
}

function formatPhone(phone) {
  if (phone.length == 9) {
    phone = "0" + phone;
  } else {
    phone = "Non renseigné";
  }
  let phoneFormated = phone.replace(
    /(\d{2})(\d{2})(\d{2})(\d{2})(\d{2})/,
    "$1.$2.$3.$4.$5"
  );
  return phoneFormated;
}

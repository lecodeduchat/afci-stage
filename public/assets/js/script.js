"use strict";
console.log("coucou");
const btnMainMenu = document.querySelector(".main_menu-btn");
const mainMenu = document.querySelector(".main_menu-list");

btnMainMenu.addEventListener("click", function () {
  console.log("click");
  mainMenu.classList.toggle("main_menu-list--open");
});
/*                                               Google maps                                        */

// import { Loader } from "@googlemaps/js-api-loader"



// const loader = new Loader({
//   apiKey: "AIzaSyCWh921gdpP63AAGNR6wB9ZENUwOCF7nPU",
//   version: "weekly",
//   ...additionalOptions,
// });

// loader.load().then(() => {
//   map = new google.maps.Map(document.getElementById("map"), {
//     center: { lat: -34.397, lng: 150.644 },
//     zoom: 10,
//   });
// });

let map;
const haubourdin = { lat: 50.611784712322354, lng: 2.975147782208914 };

function initMap() {
   map = new google.maps.Map(document.getElementById("map"), {
    center: haubourdin,
    zoom: 15,
  });

  const maker = new google.maps.Marker(
    {
        position: haubourdin,
        map: map,

    }
  )
  
}

window.initMap = initMap;


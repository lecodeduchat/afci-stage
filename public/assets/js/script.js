"use strict";
// Ouverture et fermeture du menu principale ---------------------------------
const btnMainMenu = document.querySelector(".main_menu-btn");
const mainMenu = document.querySelector(".main_menu-list");
const burger = btnMainMenu.querySelector(".fa-bars");

btnMainMenu.addEventListener("click", function () {
  console.log("click");
  mainMenu.classList.toggle("main_menu-list--open");
});


// Google Map ---------------------------------------------------------------
let map;
const haubourdin = { lat: 50.611784712322354, lng: 2.975147782208914 };



function initMap() {
   map = new google.maps.Map(document.getElementById("map"), {
    center: haubourdin,
    zoom: 15,
  });

const contentString = '<div id="content_marker">' +
'<div id="siteNotice">' +
"</div>" +
'<h4 id="firstHeading" class="firstHeading">Charlotte vandermersch</h4>' +
'<div id="bodyContent">' +
"<p>Horaires:<br> Lundi: 9:30-19h00<br>" +
"Mardi: 9h30-19h00<br>"+
"Mercredi: 9h30-19h00<br>"+
"Jeudi: 9h30-19h00<br>"+
"Vendredi:9h30-19h00"
 "</p>" +
"</div>" +
"</div>";

  const infowindow = new google.maps.InfoWindow({
    content: contentString,
    ariaLabel: "Charlotte vandermersch",
  });

  const marker = new google.maps.Marker(
    {
        position: haubourdin,
        map: map,
        title: "Hello Chiro",
    }
  )
  


window.initMap = initMap;
marker.setMap(map);

marker.addListener("click", () => {
  infowindow.open({
    anchor: marker,
    map,
  });
});
}







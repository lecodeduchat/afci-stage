"use strict";
console.log("coucou");
const btnMainMenu = document.querySelector(".main_menu-btn");
const mainMenu = document.querySelector(".main_menu-list");

btnMainMenu.addEventListener("click", function () {
  console.log("click");
  mainMenu.classList.toggle("main_menu-list--open");
});
/*                                               Google maps                                        */



let map;
const haubourdin = { lat: 50.611784712322354, lng: 2.975147782208914 };

const contentString = '<div id="content">' +
'<div id="siteNotice">' +
"</div>" +
'<h1 id="firstHeading" class="firstHeading">Uluru</h1>' +
'<div id="bodyContent">' +
"<p><b>Uluru</b>, also referred to as <b>Ayers Rock</b>, is a large " +
"sandstone rock formation in the southern part of the " +
"Northern Territory, central Australia. It lies 335&#160;km (208&#160;mi) " +
"south west of the nearest large town, Alice Springs; 450&#160;km " +
"(280&#160;mi) by road. Kata Tjuta and Uluru are the two major " +
"features of the Uluru - Kata Tjuta National Park. Uluru is " +
"sacred to the Pitjantjatjara and Yankunytjatjara, the " +
"Aboriginal people of the area. It has many springs, waterholes, " +
"rock caves and ancient paintings. Uluru is listed as a World " +
"Heritage Site.</p>" +
'<p>Attribution: Uluru, <a href="https://en.wikipedia.org/w/index.php?title=Uluru&oldid=297882194">' +
"https://en.wikipedia.org/w/index.php?title=Uluru</a> " +
"(last visited June 22, 2009).</p>" +
"</div>" +
"</div>";

function initMap() {
   map = new google.maps.Map(document.getElementById("map"), {
    center: haubourdin,
    zoom: 15,
  });

  const marker = new google.maps.Marker(
    {
        position: haubourdin,
        map: map,
        title: "Hello World",
    }
  )
  
}

window.initMap = initMap;
marker.setMap(map);


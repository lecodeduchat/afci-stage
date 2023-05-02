"use strict";

// Je teste la taille de l'écran pour afficher le calendrier en mode liste ou en mode grille
let width = window.innerWidth;
let view;
if (width < 768) {
  view = "listDay";
} else if (width < 1024) {
  view = "timeGridFourDay";
} else {
  view = "timeGridWeek";
}
let calendarElt = document.getElementById("calendar");
let calendar = new FullCalendar.Calendar(calendarElt, {
  initialView: view,
  views: {
    timeGridFourDay: {
      type: "timeGrid",
      duration: { days: 4 },
    },
  },
  // Je fais en sorte que le calendrier commence le lundi
  firstDay: 1,
  // Je change le format de l'affichage des dates des colonnes
  dayHeaderFormat: {
    weekday: "long",
    day: "numeric",
    month: "long",
  },
  //! TODO: chercher comment modifier titleFormat en fonction de la vue (mois, semaine, jour)
  titleFormat: {
    year: "numeric",
    month: "long",
  },
  locale: "fr",
  timeZone: "Europe/Paris",
  // Je place un repère pour indiquer l'heure actuelle
  nowIndicator: true,
  eventMinHeight: 30,
  // Je règle la durée des créneaux à 15 minutes et je définis les heures d'ouverture et de fermeture
  slotDuration: "00:15:00",
  slotMinTime: "08:00:00",
  slotMaxTime: "20:00:00",
  headerToolbar: {
    start: "prev,next today",
    center: "title",
    end: "dayGridMonth,timeGridWeek,timeGridDay,listDay",
  },
  // Je change le texte des boutons de navigation
  buttonText: {
    today: "Aujourd'hui",
    month: "Mois",
    week: "Semaine",
    day: "Jour",
    list: "Liste",
  },
  // Je passe à false l'option fixedWeekCount pour que le nombre de semaines affichées s'adapte au mois (4, 5 ou 6)
  fixedWeekCount: false,
  //
  events: events,
  editable: true,
  eventResizableFromStart: true,
  eventClick: function (infos) {
    console.log(infos.event.start);
    console.log(infos.event.end);
  },
});

calendar.render();

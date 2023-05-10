"use strict";

// Je teste la taille de l'écran pour afficher le calendrier en mode liste ou en mode grille
let width = window.innerWidth;
let calendarElt = document.getElementById("calendar");
let view, calendar;
if (width < 768) {
  calendar = new FullCalendar.Calendar(calendarElt, {
    initialView: "listDay",
    // Selon la vue je modifie les paramètres d'affichage
    views: {
      timeGridFourDay: {
        type: "timeGridWeek",
        duration: { days: 4 },
      },
      timeGridDay: {
        dayHeaderFormat: {
          weekday: "long",
          day: "numeric",
          month: "long",
        },
      },
      timeGridWeek: {
        dayHeaderFormat: {
          weekday: "short",
          day: "numeric",
          month: "numeric",
        },
      },
      dayGridMonth: {
        dayHeaderFormat: {
          weekday: "short",
        },
      },
    },
    // Je fais en sorte que le calendrier commence le lundi
    firstDay: 1,
    // Je fais disparaître le dimanche
    hiddenDays: [0],
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
    slotMinTime: "08:30:00",
    slotMaxTime: "20:00:00",
    headerToolbar: {
      start: "prev,next today",
      center: "title",
      end: "dayGridMonth,timeGridFourDay,timeGridDay,listWeek",
    },
    // Je change le texte des boutons de navigation
    buttonText: {
      today: "Aujourd'hui",
      month: "Mois",
      timeGridFourDay: "Semaine",
      day: "Jour",
      list: "Liste",
      allDay: "Journée entière",
    },
    // Je passe à false l'option fixedWeekCount pour que le nombre de semaines affichées s'adapte au mois (4, 5 ou 6)
    fixedWeekCount: false,
    showNonCurrentDates: false,
    // Je passe en paramètres les rendez-vous récupérés en base de données
    events: events,
    // Je rends les événements modifiables en les déplaçants
    editable: true,
    // Je rends les événements redimensionnables
    // eventResizableFromStart: true,
    eventClick: function (infos) {
      console.log(infos.event.start);
      console.log(infos.event.end);
    },
    eventDrop: function (infos) {
      if (!confirm("Etes vous sûr(e) de vouloir déplacer ce rendez-vous ?")) {
        // La méthode revert permet d'annuler les modifications
        infos.revert();
      }
    },
  });
} else if (width < 1024) {
  view = "timeGridFourDay";
  calendar = new FullCalendar.Calendar(calendarElt, {
    initialView: view,
    // Selon la vue je modifie les paramètres d'affichage
    views: {
      timeGridFourDay: {
        type: "timeGridWeek",
        duration: { days: 4 },
      },
      timeGridDay: {
        dayHeaderFormat: {
          weekday: "long",
          day: "numeric",
          month: "long",
        },
      },
      timeGridWeek: {
        dayHeaderFormat: {
          weekday: "short",
          day: "numeric",
          month: "numeric",
        },
      },
      dayGridMonth: {
        dayHeaderFormat: {
          weekday: "long",
        },
      },
    },
    // Je fais en sorte que le calendrier commence le lundi
    firstDay: 1,
    // Je fais disparaître le dimanche
    hiddenDays: [0],
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
    slotMinTime: "08:30:00",
    slotMaxTime: "20:00:00",
    headerToolbar: {
      start: "prev,next today",
      center: "title",
      end: "dayGridMonth,timeGridWeek,timeGridDay,listWeek",
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
    showNonCurrentDates: false,
    // Je passe en paramètres les rendez-vous récupérés en base de données
    events: events,
    // Je rends les événements modifiables en les déplaçants
    editable: true,
    // Je rends les événements redimensionnables
    eventResizableFromStart: true,
    eventClick: function (infos) {
      console.log(infos.event.start);
      console.log(infos.event.end);
    },
    eventDrop: function (infos) {
      if (!confirm("Etes vous sûr(e) de vouloir déplacer ce rendez-vous ?")) {
        // La méthode revert permet d'annuler les modifications
        infos.revert();
      }
    },
  });
} else if (width < 1300) {
  view = "timeGridWeek";
  calendar = new FullCalendar.Calendar(calendarElt, {
    initialView: view,
    // Selon la vue je modifie les paramètres d'affichage
    views: {
      timeGridFourDay: {
        type: "timeGridWeek",
        duration: { days: 4 },
      },
      timeGridDay: {
        dayHeaderFormat: {
          weekday: "long",
          day: "numeric",
          month: "long",
        },
      },
      timeGridWeek: {
        dayHeaderFormat: {
          weekday: "short",
          day: "numeric",
          month: "numeric",
        },
      },
      dayGridMonth: {
        dayHeaderFormat: {
          weekday: "long",
        },
      },
    },
    // Je fais en sorte que le calendrier commence le lundi
    firstDay: 1,
    // Je fais disparaître le dimanche
    hiddenDays: [0],
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
    slotMinTime: "08:30:00",
    slotMaxTime: "20:00:00",
    headerToolbar: {
      start: "prev,next today",
      center: "title",
      end: "dayGridMonth,timeGridWeek,timeGridDay,listWeek",
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
    showNonCurrentDates: false,
    // Je passe en paramètres les rendez-vous récupérés en base de données
    events: events,
    // Je rends les événements modifiables en les déplaçants
    editable: true,
    // Je rends les événements redimensionnables
    eventResizableFromStart: true,
    eventClick: function (infos) {
      console.log(infos.event.start);
      console.log(infos.event.end);
    },
    eventDrop: function (infos) {
      if (!confirm("Etes vous sûr(e) de vouloir déplacer ce rendez-vous ?")) {
        // La méthode revert permet d'annuler les modifications
        infos.revert();
      }
    },
  });
} else {
  view = "timeGridWeek";
  calendar = new FullCalendar.Calendar(calendarElt, {
    initialView: view,
    // Selon la vue je modifie les paramètres d'affichage
    views: {
      timeGridFourDay: {
        type: "timeGridWeek",
        duration: { days: 4 },
      },
      timeGridDay: {
        dayHeaderFormat: {
          weekday: "long",
          day: "numeric",
          month: "long",
        },
      },
      timeGridWeek: {
        dayHeaderFormat: {
          weekday: "short",
          day: "numeric",
          month: "numeric",
        },
      },
      dayGridMonth: {
        dayHeaderFormat: {
          weekday: "long",
        },
      },
    },
    // Je fais en sorte que le calendrier commence le lundi
    firstDay: 1,
    // Je fais disparaître le dimanche
    hiddenDays: [0],
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
    slotMinTime: "08:30:00",
    slotMaxTime: "20:00:00",
    headerToolbar: {
      start: "prev,next today",
      center: "title",
      end: "dayGridMonth,timeGridWeek,timeGridDay,listWeek",
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
    showNonCurrentDates: false,
    // Je passe en paramètres les rendez-vous récupérés en base de données
    events: events,
    // Je rends les événements modifiables en les déplaçants
    editable: true,
    // Je rends les événements redimensionnables
    eventResizableFromStart: true,
    eventClick: function (infos) {
      console.log(infos.event.start);
      console.log(infos.event.end);
    },
    eventDrop: function (infos) {
      if (!confirm("Etes vous sûr(e) de vouloir déplacer ce rendez-vous ?")) {
        // La méthode revert permet d'annuler les modifications
        infos.revert();
      }
    },
  });
}

// On écoute l'évènement eventChange pour mettre à jour la base de données
calendar.on("eventChange", function (event) {
  let url = `/calendrier/edition/${event.event.id}`;
  let data = {
    title: event.event.title,
    start: event.event.start,
    end: event.event.end,
    color: event.event.backgroundColor,
  };
  // On envoie une requête AJAX en méthode PUT
  let xhr = new XMLHttpRequest();
  xhr.open("PUT", url);
  xhr.send(JSON.stringify(data));
});
calendar.render();

// Animation du menu en mode mobile
const menuCalendarMobile = document.querySelector(".menu-calendar-mobile");
const itemsMenu = document.querySelector(".fc-toolbar-chunk:nth-of-type(3)");
menuCalendarMobile.addEventListener("click", function () {
  itemsMenu.classList.toggle("open-menu-calendar-mobile");
});

"use strict";
let width = window.innerWidth;
let events;
// Je récupère les rendez-vous en base de données
// Si je suis sur mobile, la vue étant par défaut en mode liste je n'affiche que les rendez-vous
// Si je suis sur desktop, la vue étant par défaut en mode semaine j'affiche les rendez-vous et heures d'indisponibilité
if (width < 768) {
  events = appointments;
  console.log(events);
} else {
  events = datas;
}
// Je modifie le tableau events en fonction de la vue
const btnsView = document.querySelectorAll(".fc-button-group button");
btnsView.forEach((btn) => {
  console.log("test btn", btn);
  btn.addEventListener("click", (e) => {
    updateEvents(e.target.textContent);
    console.log("test clic view", events);
  });
});
function updateEvents(view) {
  switch (view) {
    case "Jour":
    case "Semaine":
      events = datas;
      break;
    case "Mois":
    case "Liste":
      events = appointments;
      break;
  }
  calendar.render();
  console.log("test updateEvents", events);
}

// Je teste la taille de l'écran pour afficher le calendrier en mode liste ou en mode grille

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
// ------------------------------------------------------------------------------------------------------
// Animation du menu en mode mobile
const menuCalendarMobile = document.querySelector(".menu-calendar-mobile");
const itemsMenu = document.querySelector(".fc-toolbar-chunk:nth-of-type(3)");
let buttonsView, xStart, rows, columns, columnHours;
let days = [];
let cpt = 0;
// Valeur de la marge (margin-left) du calendar sur mobile
let marge = 8;

// Je mets un écouteur d'évènement sur le bouton du menu en mode mobile
menuCalendarMobile.addEventListener("click", function () {
  itemsMenu.classList.toggle("open-menu-calendar-mobile");
  buttonsView = document.querySelectorAll(".fc-button-group button");
  console.log("test buttonsView mobile", buttonsView[0]);
  // Je mets un écouteur d'évènement sur chaque bouton de vue du calendrier
  buttonsView.forEach((button) => {
    button.addEventListener("click", (e) => {
      getDateTime(marge);
      updateEvents(e.target.textContent);
      console.log(e.target.textContent);
    });
  });
});

// Je lance la fonction pour récupérer les dates et heures du calendrier si on est en mode desktop
if (window.innerWidth > 768) {
  const menu = document.querySelector(".menu_container");
  marge = menu.offsetWidth + 18;
  console.log("marge", marge);
  getDateTime(marge);
}
/**
 * Fonction qui récupère les dates et heures du calendrier
 */
function getDateTime(marge) {
  // Je récupère les lignes du calendrier
  rows = document.querySelectorAll(".fc-timegrid-slot-lane");
  //! Je récupère les colonnes du calendrier correspondant aux jours (les colonnes des heures sont exclues)
  columnHours = document.querySelector(".fc-timegrid-col");
  columns = document.querySelectorAll(".fc-timegrid-col.fc-day");
  // Je récupère la largeur de la première colonne et j'ajoute 8px de marge pour avoir le début de la première case
  if (columnHours) {
    xStart = columnHours.offsetWidth + marge;
    console.log(
      "xStart",
      xStart,
      "columnHours.offsetWidth",
      columnHours.offsetWidth,
      "marge",
      marge
    );
  }
  columns.forEach((column) => {
    let date = column.getAttribute("data-date");
    days[cpt] = {
      date: date,
      xStart: xStart,
      xEnd: xStart + column.offsetWidth,
    };
    cpt++;
    xStart += column.offsetWidth;
  });
  console.log(days);
  // Je place un écouteur d'évènement sur chaque case du calendrier
  rows.forEach((row) => {
    row.addEventListener("click", (e) => {
      let time = row.getAttribute("data-time");
      let x = e.clientX;
      console.log("test", e.clientX, "time", time);
      days.forEach((day) => {
        console.log("day", day.xStart, day.xEnd);
        if (x >= day.xStart && x <= day.xEnd) {
          console.log(day.date, time);
          localStorage.setItem("date", day.date);
          localStorage.setItem("time", time);
        }
      });

      document.location.href = "/admin/rendez-vous/nouveau";
      getDateTime(marge);
    });
  });
}

// ------------------------------------------------------------------------------------------------------
// Ecouteurs d'évènements sur les boutons de navigation du calendrier prev et next
const prev = document.querySelector(".fc-prev-button");
const next = document.querySelector(".fc-next-button");
prev.addEventListener("click", () => {
  getDateTime(marge);
});
next.addEventListener("click", () => {
  getDateTime(marge);
});

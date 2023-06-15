"use strict";

const cellPhone = document.getElementById("registration_form_cell_phone");

let phone = formatPhone(cellPhone.value);
cellPhone.value = phone;

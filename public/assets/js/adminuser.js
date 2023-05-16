const cellPhones = document.querySelectorAll(".cellPhone");
const homePhones = document.querySelectorAll(".homePhone");

cellPhones.forEach((cellPhone) => {
   let cellPhoneValue = cellPhone.textContent; 
   cellPhone.textContent = formatPhone(cellPhoneValue);
})

homePhones.forEach((homePhone)=> {
    let homePhoneValue = homePhone.textContent;
    homePhone.textContent = formatPhone(homePhoneValue);
})




function formatPhone(phone) {
  phone = "0" + phone;
  let phoneFormated = phone.replace(
    /(\d{2})(\d{2})(\d{2})(\d{2})(\d{2})/,
    "$1 $2 $3 $4 $5"
  );
  return phoneFormated;
}



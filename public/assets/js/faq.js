"use strict";
const questions = document.querySelectorAll(".faq_question");
questions.forEach((question) => {
  const answer = question.nextElementSibling;
  question.addEventListener("click", () => {
    answer.classList.toggle("faq_answer--open");
  });
});

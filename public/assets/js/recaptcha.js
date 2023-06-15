// cle du site
let cleCaptcha = "6Ley7lsmAAAAABFlnMA7qvP806dBQ50ZeOtmA84b";

function onClick(e) {
<<<<<<< HEAD
    e.preventDefault();
    grecaptcha.ready(function() {
      grecaptcha.execute('6LfhAlwmAAAAABkY8ssrGY_uLMmnDa5R7I8Cosq8', {action: 'submit'}).then(function(token) {
          // Add your logic to submit to your backend server here.
=======
  e.preventDefault();
  grecaptcha.ready(function () {
    grecaptcha
      .execute("6LfhAlwmAAAAABkY8ssrGY_uLMmnDa5R7I8Cosq8", { action: "submit" })
      .then(function (token) {
        // Add your logic to submit to your backend server here.
>>>>>>> main
      });
  });
}

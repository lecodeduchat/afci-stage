// cle du site
let cleCaptcha = "";

function onClick(e) {
    e.preventDefault();
    grecaptcha.ready(function() {
      grecaptcha.execute('', {action: 'submit'}).then(function(token) {
          // Add your logic to submit to your backend server here.



      });
  });
}

function onClick(e) {
    e.preventDefault();
    grecaptcha.ready(function() {
      grecaptcha.execute('cle captcha', {action: 'submit'}).then(function(token) {
          // Add your logic to submit to your backend server here.
      });
    });
  }
$(document).ready(function() {
  $('#loginform').submit(function(e) {
    e.preventDefault();
    $.ajax({
       type: "POST",
       url: '/login-php.php',
       data: $(this).serialize(),
       success: function(data)
       {
          if (data === 'Login') {
            window.location = '/ok';
          }
          else {
            alert('Invalid Credentials');
          }
       }
   });
 });
});
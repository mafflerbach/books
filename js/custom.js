$(document).ready(function () {
  $('#login').click(function (e) {
    e.preventDefault();
    $.ajax({
      url: "cmd.php",
      type: "POST",
      data: {
        password: $('#password').val(),
        username: $('#loginname').val(),
        cmd: 'login'
      },
      dataType: "json"
    }).done(function (data) {
        if (data) {
          window.location = 'index.php';
        } else {
          $('.wrongPassword').fadeIn();
          console.log('false');
        }
      })
  });

  $('#signup').click(function (e) {
    e.preventDefault();

    if($('#usernamesignup').val() == '') {
      $('#usernamesignup').addClass('error');
      $('.required').fadeIn();
    }
    if($('#passwordsignup').val() == '') {
      $('#passwordsignup').addClass('error');
      $('.required').fadeIn();
    }
    if($('#emailsignup').val() == '') {
      $('#emailsignup').addClass('error');
      $('.required').fadeIn();
    }

    $.ajax({
      url: "cmd.php",
      type: "POST",
      data: {
        password: $('#passwordsignup').val(),
        username: $('#usernamesignup').val(),
        email: $('#emailsignup').val(),
        cmd: 'signup'
      },
      dataType: "json"
    }).done(function (data) {

        if (data == 'username') {
          $('.usernameExist').fadeIn();
        }
        if (data == 'email') {
          $('.emailExist').fadeIn();
        }

      })
  });

  $('.to_register').click(function (e) {
    e.preventDefault();
    $('.main').hide();
    $('.registermain').fadeIn();
  });

  $('.registerUser').click(function (e) {
    e.preventDefault();
    $('.registermain').hide();
    $('.main').fadeIn();
  });
})

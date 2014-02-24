$(document).ready(function () {
  $('#submit').click(function (e) {
    e.preventDefault();
    $.ajax({
      url: "cmd.php",
      type: "POST",
      data: {
        password: $('#password').val(),
        username: $('#login').val(),
        cmd: 'login'
      },
      dataType: "json"
    }).done(function (data) {
        if (data) {
          console.log('true');
        } else {
          $('.wrongPassword').fadeIn();
          console.log('false');
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

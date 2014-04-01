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

$('#addBook').click(function () {
  var content = '<div id="dd" class="easyui-dialog" title="Add Book" data-options="iconCls:\'icon-save\'"><input id="bookName" value="" type="text"/></div>';
  $('body').append(content);

  var dialog = $('#dd').dialog({
    title: 'Add',
    width: 400,
    height: 200,
    cache: false,
    modal: true,
    buttons: {
      Ok: function() {
        if ($('#bookName').val() != "") {
          $.ajax({
            url: "cmd.php",
            type: "POST",
            data: {
              cmd: 'add',
              text: $('#bookName').val(),
              type: 'book'
            }
          }).done(function (data) {
            });

          $(this).dialog('close');
          $('#dd').remove();
        }
      },
      Cancel: function() {
        $(this).dialog( "close" );
      }
    }
  });
});

$('.delete').click(function () {
  var content = '<div id="dd" class="easyui-dialog" title="Delete" data-options="iconCls:\'icon-save\'">Wollen sie wirklich löschen?</div>';
  var _this = $(this);

  $('body').append(content);

  var dialog = $('#dd').dialog({
    title: 'Add',
    width: 400,
    height: 200,
    cache: false,
    modal: true,
    buttons: {
      Ok: function() {
        if ($('#bookName').val() != "") {
          $.ajax({
            url: "cmd.php",
            type: "POST",
            data: {
              cmd: 'remove',
              id: _this.attr('id').replace('book_',''),
              type: 'book'
            }
          })

          $(this).dialog('close');
          $('#dd').remove();
        }
      },
      Cancel: function() {
        $(this).dialog( "close" );
      }
    }
  });

});




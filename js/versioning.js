$(document).ready(function () {


  $('a[data-revert]').click(function (e) {
    e.preventDefault();
    var _this = $(this);

    var title = 'Revert'
    var content = '<div id="dd" class="easyui-dialog" title="' + title + '">Are you sure? You lost your changes!</div>';
    $('body').append(content);
    var dialog = $('#dd').dialog({
      title: title,
      width: 400,
      height: 200,
      cache: false,
      modal: true,
      buttons: {
        Ok: function () {
          $.ajax({
            url: "cmd.php",
            type: "POST",
            data: {
              cmd: 'revert',
              bookId: _this.attr('data-bookId'),
              rev: _this.attr('data-revert')
            }
          }).done(function (data) {
            console.log(data);
          });

          $(this).dialog("close");
          $('#dd').remove();
        },
        Cancel: function () {
          $(this).dialog("close");
          $('#dd').remove();
        }
      }
    });
  });

  $('a[data-commit]').click(function (e) {
    e.preventDefault();
    var _this = $(this);

    var title = 'Commit'
    var content = '<div id="dd" class="easyui-dialog" title="' + title + '"><label>Commit message</label><input id="message" value="" type="text"/></div>';
    $('body').append(content);
    var dialog = $('#dd').dialog({
      title: title,
      width: 400,
      height: 200,
      cache: false,
      modal: true,
      buttons: {
        Ok: function () {
          $.ajax({
            url: "cmd.php",
            type: "POST",
            data: {
              cmd: 'commit',
              bookId: _this.attr('data-bookId'),
              message: $('#message').val()
            }
          }).done(function (data) {
            console.log(data);
          });

          $(this).dialog("close");
          $('#dd').remove();
        },
        Cancel: function () {
          $(this).dialog("close");
          $('#dd').remove();
        }
      }
    });
  });


  $('a[data-compare]').click(function (e) {
    $(this).toggleClass('inactive');
    $(this).toggleClass('active');


    var parent = $(this).parent('li');

    $('a[data-compare].active')

    if (parent.children('a[data-compare].active').length > 2 || parent.children('a[data-compare].active').length < 2) {
      $('#compareoutput').empty();
    }


    if (parent.children('a[data-compare].active').length == 2) {

      var rev1 = $($('a[data-compare].active').get(0)).data('compare');
      var rev2 = $($('a[data-compare].active').get(1)).data('compare');
      var bookId = $($('a[data-compare].active').get(0)).data('bookid');

      $.ajax({
        url: "cmd.php",
        type: "POST",
        data: {
          cmd: 'compare',
          revisions: [rev1, rev2],
          bookId: bookId
        }
      }).done(function (data) {
        $('#compareoutput').empty();
        $('#compareoutput').append(data);
      });

    }


  })

})

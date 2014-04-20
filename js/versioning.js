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
})
;

function commit() {

}
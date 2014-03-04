function supportMultiple() {
  //do I support input type=file/multiple
  var el = document.createElement("input");

  return ("multiple" in el);
}

function initUpload() {
  if(supportMultiple()) {
    document.querySelector("#multipleFileLabel").setAttribute("style","");
  }
}

$(document).ready(function () {

  $('.export').click(function () {
    var _this = $(this);
    $.ajax({
      url: "cmd.php",
      type: "POST",
      data: {
        cmd: 'export',
        bookId: _this.attr('id').replace('book_',
          '')
      }
    });
  })

  $('a[data-page]').click(function() {
    var page = $(this).data('page');

    $.ajax({
      url: "cmd.php",
      type: "POST",
      data: {
        cmd: 'getPage',
        page: page
      },
      dataType: 'html'
    }).done(function (data) {
        if ($('.scroller').length > 0) {
          $('.scroller').remove();
        }
      $('#mp-pusher').append(data);
      });
  });

  $('.edit').click(function () {
    var _this = $(this);
    $.ajax({
      url: "cmd.php",
      type: "POST",
      data: {
        cmd: 'edit',
        bookId: _this.attr('id').replace('book_',
          '')
      }
    }).done(function (data) {
        $('#tt').empty();
        if ($('.scroller').length == 0) {
          $('#mp-pusher').append(data);
        }
        initTree(_this.attr('id').replace('book_',
          ''));
        $('#cc').layout();

      });
  });

  $('.delete').click(function () {
    var content = '<div id="dd" class="easyui-dialog" title="Delete" data-options="iconCls:\'icon-save\'">Wollen sie wirklich löschen?</div>';
    var _this = $(this);

    $('body').append(content);
    var dialog = $('#dd').dialog({
      title: 'My Dialog',
      width: 400,
      height: 200,
      cache: false,
      modal: true,
      buttons: [
        {
          text: 'Ok',
          iconCls: 'icon-ok',
          handler: function () {
            $.ajax({
              url: "cmd.php",
              type: "POST",
              data: {
                cmd: 'remove',
                id: _this.attr('id').replace('book_',''),
                type:'book'
              }
            }).done(function (data) {
                _this.remove();
                dialog.dialog('close');
                $('#dd').remove();
              });
          }
        },
        {
          text: 'Cancel',
          handler: function () {
            dialog.dialog('close');
            $('#dd').remove();
          }
        }
      ]
    });
  });

  $('#addBook').click(function () {
    var content = '<div id="dd" class="easyui-dialog" title="Add Book" data-options="iconCls:\'icon-save\'"><input id="bookName" value="" type="text"/></div>';
    $('body').append(content);
    var dialog = $('#dd').dialog({
      title: 'My Dialog',
      width: 400,
      height: 200,
      cache: false,
      modal: true,
      buttons: [
        {
          text: 'Ok',
          iconCls: 'icon-ok',
          handler: function () {
            if ($('#bookName').val() != "") {
              $.ajax({
                url: "cmd.php",
                type: "POST",
                data: {
                  cmd: 'add',
                  text: $('#bookName').val(),
                  type: 'book'
                }
              }).done(function () {
                  $('#tt').empty();
                  initTree();
                });

              dialog.dialog('close');
              $('#dd').remove();
            }
          }
        },
        {
          text: 'Cancel',
          handler: function () {
            dialog.dialog('close');
            $('#dd').remove();
          }
        }
      ]
    });
  });
})

function initTree(bookId) {

  $('#tt').tree({
    animate: true,
    url: 'cmd.php?cmd=getTree&id=' + bookId,
    dnd: true,
    onBeforeDrop: function (target, source, point) {
      if (($(target).attr('data-book') == 'undefined' && point == 'append') ||
          ($(target).attr('data-book') != 'undefined' && point == 'top') ||
          ($(target).attr('data-book') != 'undefined' && point == 'bottom')) {
        return false
      }
      if (source.section && $(target).attr('data-chapter') != 'undefined') {
        return false;
      }
      if ($(target).attr('data-book') != 'undefined' && source.section != undefined) {
        return false;
      }
      if ($(target).attr('data-book') != 'undefined' && source.book != undefined) {
        return false;
      }
    },
    onDrop: function (target, source, point) {

      var parent = '';
      var list = '';
      var type = '';
      var tmp = new Array();
      var id = '';

      if (source.section) {
        parent = $(target).parent().parent().parent().children('div');
        list = parent.parent().find('div[data-section]');
        type = 'section';
        list.each(function () {
          tmp.push($(this).attr('data-section'));
        })
      }

      if (source.chapter) {
        parent = $(target).parent().parent().parent().children('ul');
        list = parent.find("div[data-chapter!='undefined']");
        type = 'chapter';
        list.each(function () {
          tmp.push($(this).attr('data-chapter'));
        })
      }

      if (list != '' && parent != '' && type != '') {
        $.ajax({
          url: "cmd.php",
          type: "POST",
          data: {
            node: tmp.toSource(),
            cmd: 'update',
            type: type
          },
          dataType: "json"
        })
      }
    },
    onContextMenu: function (e, node) {
      e.preventDefault();
      $(this).tree('select',
        node.target);
      $('#mm').menu('show',
        {
          left: e.pageX,
          top: e.pageY
        });
    },
    onSelect: function (node) {
      $('.editor').empty();

      if (node.section) {
        $.ajax({
          url: "cmd.php",
          type: "POST",
          data: {
            id: node.id,
            cmd: 'getSection'
          },
          dataType: "json"
        }).done(function (data) {
            var content = '<textarea id="editor" data-origin="textarea" style="height:100%; width:100%;"></textarea>';
            $('.editor').append(content);
            initEditor();
            $("#editor").jqteVal(data.content);
          });
      }
    }
  });
}

var TreeAction = {
  append: function () {
    var node = $('#tt').tree('getSelected');

    if (node.book || node.chapter) {
      var content = '<div id="dd" class="easyui-dialog" title="Add" data-options="iconCls:\'icon-save\'"><input id="name" value="" type="text"/></div>';

      $('body').append(content);
      var dialog = $('#dd').dialog({
        title: 'My Dialog',
        width: 400,
        height: 200,
        cache: false,
        modal: true,
        buttons: [
          {
            text: 'Ok',
            iconCls: 'icon-ok',
            handler: function () {
              if ($('#chapterName').val() != "") {

                var node = $('#tt').tree('getSelected');
                $('#tt').tree('append',
                  {
                    parent: node.target,
                    data: [
                      {
                        text: $('#name').val()
                      }
                    ]
                  });

                var action = '';
                var type = '';

                if (node.book) {
                  type = 'chapter';
                }
                if (node.chapter) {
                  type = 'section';
                }

                $.ajax({
                  url: "cmd.php",
                  type: "POST",
                  data: {
                    id: node.id,
                    cmd: 'add',
                    text: $('#name').val(),
                    type: type
                  },
                  dataType: "json"
                }).done(function (data) {
                    $('#tt').empty();
                    initTree();
                  });

                dialog.dialog('close');
                $('#dd').remove();
              }
            }
          },
          {
            text: 'Cancel',
            handler: function () {
              dialog.dialog('close');
              $('#dd').remove();
            }
          }
        ]
      });

    } else {
      console.log('kann kein subcapitel erstellen')
    }
  },

  rename: function () {
    var node = $('#tt').tree('getSelected');
    var content = '<div id="dd" class="easyui-dialog" title="Rename" data-options="iconCls:\'icon-save\'"><input id="rename" value="" type="text"/></div>';

    $('body').append(content);

    var dialog = $('#dd').dialog({
      title: 'My Dialog',
      width: 400,
      height: 200,
      cache: false,
      modal: true,
      buttons: [
        {
          text: 'Ok',
          iconCls: 'icon-ok',
          handler: function () {
            if ($('#rename').val() != "") {
              dialog.dialog('close');
              $('#tt').tree('update',
                {
                  target: node.target,
                  text: $('#rename').val()
                });

              var type = '';
              if (node.book) {
                type = 'book';
              }
              if (node.section) {
                type = 'section';
              } else {
                type = 'chapter';
              }

              $.ajax({
                url: "cmd.php",
                type: "POST",
                data: {
                  id: node.id,
                  cmd: 'rename',
                  type: type,
                  text: $('#rename').val()
                },
                dataType: "json"
              })
              $('#dd').remove();
            }
          }
        },
        {
          text: 'Cancel',
          handler: function () {
            dialog.dialog('close');
            $('#dd').remove();
          }
        }
      ]
    });
  },

  removeit: function () {
    var node = $('#tt').tree('getSelected');
    var content = '<div id="dd" class="easyui-dialog" title="Delete" data-options="iconCls:\'icon-save\'">Wollen sie wirklich löschen?</div>';

    $('body').append(content);
    var dialog = $('#dd').dialog({
      title: 'My Dialog',
      width: 400,
      height: 200,
      cache: false,
      modal: true,
      buttons: [
        {
          text: 'Ok',
          iconCls: 'icon-ok',
          handler: function () {
            if ($('#chapterName').val() != "") {
              $('#tt').tree('remove',
                node.target);
              dialog.dialog('close');
              var type = '';
              if (node.book != undefined) {
                type = 'book';
              } else {
                type = 'chapter';
              }

              $.ajax({
                url: "cmd.php",
                type: "POST",
                data: {
                  id: node.id,
                  type: type,
                  cmd: 'remove'
                },
                dataType: "json"
              });
              $('#dd').remove();
            }
          }
        },
        {
          text: 'Cancel',
          handler: function () {
            dialog.dialog('close');
            $('#dd').remove();
          }
        }
      ]
    });
  },

  collapse: function () {
    var node = $('#tt').tree('getSelected');
    $('#tt').tree('collapse',
      node.target);
  },
  expand: function () {
    var node = $('#tt').tree('getSelected');
    $('#tt').tree('expand',
      node.target);
  }

};
$(document).ready(function () {

  $('.export').click(function () {
    var _this = $(this);

    console.log(_this);
    $.ajax({
      url: "cmd.php",
      type: "POST",
      data: {
        cmd: 'export',
        bookId: _this.attr('value')
      }
    }).done(function (data) {
        console.log(data);
      });

  })


  $('#addBook').click(function () {
    var content = '<div id="dd" class="easyui-dialog" title="My Dialog" data-options="iconCls:\'icon-save\',resizable:true,modal:true"><input id="bookName" value="" type="text"/></div>';
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
                  cmd: 'addBook',
                  text: $('#bookName').val()
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

function initTree() {

  $('#tt').tree({
    animate: true,
    url: 'cmd.php?cmd=getTree',
    dnd: true,
    onBeforeDrop: function (target, source, point) {
      if (($(target).attr('data-book') == 'undefined' && point == 'append') ||
        ($(target).attr('data-book') != 'undefined' && point == 'top') ||
        ($(target).attr('data-book') != 'undefined' && point == 'bottom')) {
        return false
      }
      if (source.section && $(target).attr('data-chapter') != 'undefined' ) {
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
        var tmp  = new Array();
        var id = '';

        if (source.section) {
            parent = $(target).parent().parent().parent().children('div');
            list = parent.parent().find('div[data-section]');
            type = 'updateSection';
            list.each(function () {
                tmp.push($(this).attr('data-section'));
            })
        }

        if (source.chapter) {
            parent = $(target).parent().parent().parent().children('ul');
            list = parent.find("div[data-chapter!='undefined']");
            type = 'updateChapter';
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
                    cmd: type
                },
                dataType: "json"
            }).done(function (data) {
                });
        }
    },
    onContextMenu: function (e, node) {
      e.preventDefault();
      $(this).tree('select', node.target);
      $('#mm').menu('show', {
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
            console.log(data)
          });
      }
    }
  });
}

var TreeAction =
{
  append: function () {
    var node = $('#tt').tree('getSelected');

    if (node.book || node.chapter) {
      var content = '<div id="dd" class="easyui-dialog" title="My Dialog" data-options="iconCls:\'icon-save\',resizable:true,modal:true"><input id="chapterName" value="" type="text"/></div>';

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
                $('#tt').tree('append', {
                  parent: node.target,
                  data: [
                    {
                      text: $('#chapterName').val()
                    }
                  ]
                });

                var action = '';
                if (node.book) {
                  action = 'addChapter';
                } else {
                  action = 'addSection';
                }

                $.ajax({
                  url: "cmd.php",
                  type: "POST",
                  data: {
                    id: node.id,
                    cmd: action,
                    text: $('#chapterName').val()
                  },
                  dataType: "json"
                }).done(function (data) {

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
    var content = '<div id="dd" class="easyui-dialog" title="My Dialog" data-options="iconCls:\'icon-save\',resizable:true,modal:true"><input id="rename" value="" type="text"/></div>';

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
              $('#tt').tree('update', {
                target: node.target,
                text: $('#rename').val()
              });

              console.log(node);

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
                  cmd: 'rename',
                  type: type,
                  text: $('#rename').val()
                },
                dataType: "json"
              }).done(function (data) {
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

  removeit: function () {
    var node = $('#tt').tree('getSelected');
    var content = '<div id="dd" class="easyui-dialog" title="My Dialog" data-options="iconCls:\'icon-save\',resizable:true,modal:true">Wollen sie wirklich löschen?</div>';

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
              $('#tt').tree('remove', node.target);
              dialog.dialog('close');
              console.log(node);

              var type = '';
              if (node.book != undefined) {
                type = 'removeBook';
              } else {
                type = 'removeChapter';
              }

              $.ajax({
                url: "cmd.php",
                type: "POST",
                data: {
                  id: node.id,
                  cmd: type                                },
                dataType: "json"
              }).done(function () {
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
    $('#tt').tree('collapse', node.target);
  },
  expand: function () {
    var node = $('#tt').tree('getSelected');
    $('#tt').tree('expand', node.target);
  }

};
function supportMultiple() {
  //do I support input type=file/multiple
  var el = document.createElement("input");

  return ("multiple" in el);
}

function initUpload() {
  if (supportMultiple()) {
    document.querySelector("#multipleFileLabel").setAttribute("style",
      "");
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

  $('a[data-page]').click(function () {
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
        $('.scroller').empty();
        $('.scroller').append(data);
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
        $('.scroller').empty();
        $('.scroller').append(data); /*
        initTree(_this.attr('id').replace('book_',
          ''));
        $('#cc').layout(); */

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
                id: _this.attr('id').replace('book_',
                  ''),
                type: 'book'
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
                 // initTree();
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
  }
};


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

var DirAction = {
  append: function () {
    var node = $('#tt').tree('getSelected');

    var fileUpload = '<div class="fileupload">' + '<h2>Upload</h2>' + '<div class="uploadDialog">' +
                     '<form action = "upload.php" method="post" enctype="multipart/form-data">' +
                     '<input type="file" name="imageURL[]" id="imageURL" multiple="" />' +
                     '<input type="submit" value="submit" name="submit" />' + '</form>' + '</div>' + '</div>';

    var dir = '<div class="addDir">' + '<h2>Add Dir</h2>' + '<div class="addDirDialog">' +
              '<form action="" method="post">' + '<input type="text" name="dirname" id="dirname" />' +
              '<button value="Add Dir" name="submit" id="adddir">Add Dir</button>' + '</form>' + '</div>' + '</div>';

    var content = '<div id="dd" class="easyui-dialog"  title="Add" data-options="iconCls:\'icon-save\'">' + dir +
                  fileUpload + '</div>';

    $('body').append(content);
    var dialog = $('#dd').dialog({
      title: 'Add',
      cache: false,
      modal: true,
      width: 300,
      height: 300,
      buttons: [
        {
          text: 'Ok',
          iconCls: 'icon-ok',
          handler: function () {

            var node = $('#filetree').tree('getSelected');

            $('#tt').tree('append',
              {
                parent: node.target,
                data: [
                  {
                    text: $('#name').val()
                  }
                ]
              });
            console.log(node);
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

    $('.fileupload h2').click(function () {
      $('.addDirDialog').hide();
      if ($('.uploadDialog:visible').length == 1) {
        $('.uploadDialog').hide();
      } else {
        $('.uploadDialog').show();
      }
    })
    $('.addDir h2').click(function () {
      $('.uploadDialog').hide();
      if ($('.addDirDialog:visible').length == 1) {
        $('.addDirDialog').hide();
      } else {
        $('.addDirDialog').show();
      }
    })

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
  }
};
function initFiletree() {
    $("#tree").fancytree({
      extensions: ["menu", "glyph", "dnd"],
      menu: {
        selector: "#myMenu",
        position: {my: "center"},
        create: function(event, data){
          //$.ui.fancytree.debug("Menu create ", data.$menu);
        },
        open: function(event, data){
          //$.ui.fancytree.debug("Menu open ", data.$menu, data.node);
        },
        focus: function(event, data){
          //$.ui.fancytree.debug("Menu focus ", data.menuId, data.node);
        },
        select: function(event, data){
          console.log("Menu select " + data.menuId + ", " + data.node);
        },
        close: function(event, data){
          //$.ui.fancytree.debug("Menu close ", data.$menu, data.node);
        }
      },
      glyph: {
        map: {
          doc: "fa fa-file-o",
          docOpen: "fa fa-font",
          folder: "fa fa-folder",
          folderOpen: "fa fa-folder-open",
          expanderOpen: "fa fa-plus-square-o ",
          expanderLazy: "fa fa-minus-square-o ",
          expanderClosed: "fa  fa-circle-o "
        }
      },
      dnd: {
        preventVoidMoves: true, // Prevent dropping nodes 'before self', etc.
        preventRecursiveMoves: true, // Prevent dropping nodes on own descendants
        autoExpandMS: 400,
        dragStart: function(node, data) {
          /** This function MUST be defined to enable dragging for the tree.
           *  Return false to cancel dragging of node.
           */
          return true;
        },
        dragEnter: function(node, data) {
          /** data.otherNode may be null for non-fancytree droppables.
           *  Return false to disallow dropping on node. In this case
           *  dragOver and dragLeave are not called.
           *  Return 'over', 'before, or 'after' to force a hitMode.
           *  Return ['before', 'after'] to restrict available hitModes.
           *  Any other return value will calc the hitMode from the cursor position.
           */
          // Prevent dropping a parent below another parent (only sort
          // nodes under the same parent)
          /*           if(node.parent !== data.otherNode.parent){
           return false;
           }
           // Don't allow dropping *over* a node (would create a child)
           return ["before", "after"];
           */
          return true;
        },
        dragDrop: function(node, data) {
          /** This function MUST be defined to enable dropping of items on
           *  the tree.
           */
          data.otherNode.moveTo(node, data.hitMode);
        }
      }
    });
}
function initBooktree() {
    $(".bookmenu").fancytree({
      click: function(event, data) {

        console.log(event, data, ", targetType=" + data.targetType);

        if (data.node.data.section) {
          $.ajax({
            url: "cmd.php",
            type: "POST",
            data: {
              id: data.node.data.id,
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


      },
      extensions: ["menu", "glyph", "dnd"],
      menu: {
        selector: "#myMenu",
        position: {my: "center"},
        create: function(event, data){
          //$.ui.fancytree.debug("Menu create ", data.$menu);
        },
        open: function(event, data){
          //$.ui.fancytree.debug("Menu open ", data.$menu, data.node);
        },
        focus: function(event, data){
          //$.ui.fancytree.debug("Menu focus ", data.menuId, data.node);
        },
        select: function(event, data){
          console.log("Menu select " + data.menuId + ", " + data.node);
        },
        close: function(event, data){
          //$.ui.fancytree.debug("Menu close ", data.$menu, data.node);
        }
      },
      glyph: {
        map: {
          doc: "fa fa-file-o",
          docOpen: "fa fa-font",
          folder: "fa fa-folder",
          folderOpen: "fa fa-folder-open",
          expanderOpen: "fa fa-plus-square-o ",
          expanderLazy: "fa fa-minus-square-o ",
          expanderClosed: "fa  fa-circle-o "
        }
      },
      dnd: {
        preventVoidMoves: true, // Prevent dropping nodes 'before self', etc.
        preventRecursiveMoves: true, // Prevent dropping nodes on own descendants
        autoExpandMS: 400,
        dragStart: function(node, data) {
          /** This function MUST be defined to enable dragging for the tree.
           *  Return false to cancel dragging of node.
           */
          return true;
        },
        dragEnter: function(node, data) {
          /** data.otherNode may be null for non-fancytree droppables.
           *  Return false to disallow dropping on node. In this case
           *  dragOver and dragLeave are not called.
           *  Return 'over', 'before, or 'after' to force a hitMode.
           *  Return ['before', 'after'] to restrict available hitModes.
           *  Any other return value will calc the hitMode from the cursor position.
           */
          // Prevent dropping a parent below another parent (only sort
          // nodes under the same parent)
          /*           if(node.parent !== data.otherNode.parent){
           return false;
           }
           // Don't allow dropping *over* a node (would create a child)
           return ["before", "after"];
           */
          return true;
        },
        dragDrop: function(node, data) {
          /** This function MUST be defined to enable dropping of items on
           *  the tree.
           */
          data.otherNode.moveTo(node, data.hitMode);
        }
      }
    });
}



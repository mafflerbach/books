$(document).ready(function (){
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
});


function initFiletree() {
  $("#imagetree").fancytree({
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
        DirAction.append();
        console.log(data.menuId);
        console.log(data.node);
      },
      close: function(event, data){
        //$.ui.fancytree.debug("Menu close ", data.$menu, data.node);
      }
    },
    autoActivate: true,
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
    },
    autoActivate: true,
    inExpandLevel: 0
  });
}

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

var DirAction = {
  append: function () {

    var dir = '<div class="addDir">' + '<h2>Add Dir</h2>' + '<div class="addDirDialog">' +
              '<form action="" method="post">' + '<input type="text" name="dirname" id="dirname" />' +
              '<button value="Add Dir" name="submit" id="adddir">Add Dir</button>' + '</form>' + '</div>' + '</div>';

    var content = dir;

    $("#fileupload").submit(function(e){
      return false;
    });

    $('body').on('click', '#fileuploadButton', function(e) {
      alert('foo');
    });


    function request() {

    }

    createDialog(content,
      'Add',
      request);

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
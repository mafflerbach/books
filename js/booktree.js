$(document).ready(function () {
  $('.edit').click(function () {
    var _this = $(this);
    loadPage($(this).attr('id').replace('book_',''))


  });
});

function loadPage(id) {
  $.ajax({
    url: "cmd.php",
    type: "POST",
    data: {
      cmd: 'edit',
      bookId: id
    }
  }).done(function (data) {
      $('.scroller').empty();
      $('.scroller').append(data);
    });
}

function initBooktree() {
  $(".bookmenu").fancytree({
    click: function(event, data) {
      console.log(event, data, ", targetType=" + data.targetType);
      var sectionId = data.node.data.id;
      if (data.node.data.section) {
        $.ajax({
          url: "cmd.php",
          type: "POST",
          data: {
            id: sectionId,
            cmd: 'getSection'
          },
          dataType: "json"
        }).done(function (data) {
            $('.editor').empty();
            var content = '<textarea id="editor" data-section="'+sectionId+'" data-origin="textarea" style="height:100%; width:100%;"></textarea>';
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
        if (data.node.data.book) {
          TreeAction.append('chapter', data.node.data.id);
        }
        if (data.node.data.chapter) {
          TreeAction.append('section', data.node.data.id);
        }
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
  $(".bookmenu").fancytree("getRootNode").visit(function(node){
    node.setExpanded(true);
  });
}


var TreeAction = {
  append: function (type, id) {
    var content = '<div id="addSubitem" class="easyui-dialog" title="Add" data-options="iconCls:\'icon-save\'"><input id="name" value="" type="text"/></div>';

    $('body').append(content);
    var dialog = $('#addSubitem').dialog({
      title: 'Add',
      width: 400,
      height: 200,
      cache: false,
      modal: true,
      buttons: {
        Ok: function() {
          var text = $('#name').val();
          if ($('#name').val() != "") {
            $.ajax({
              url: "cmd.php",
              type: "POST",
              data: {
                id:  id,
                cmd: 'add',
                text: text,
                type: type
              }
            }).done(function (data) {
                var rootNode = $(".bookmenu").fancytree("getRootNode");
                var id = rootNode.children[0].data.id;
                loadPage(id);
              });
            $(this).dialog("close");
            $('#addSubitem').remove();
          }
        },
        Cancel: function() {
          $(this).dialog("close");
          $('#addSubitem').remove();
        }
      }
    });

    function addNode(text) {
      var tree = $(".bookmenu").fancytree("getTree");
      var node = tree.getActiveNode();
      var newData = {title: text};
      node.addChildren(newData);

    }
  },

  rename: function () {
  },

  removeit: function () {
  }
};

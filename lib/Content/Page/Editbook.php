<?php
namespace Content\Page;

class Editbook {
  public function content() {

    $bookMenu = '
                <div class="bookmenu">
                  <ul id="tt" class="easyui-tree"></ul>
                </div>';
    $editor = '<div class="editor">
               </div>';

    $treeMenue = '<div id="mm" class="easyui-menu" style="width:120px;">
                  <div onclick="TreeAction.append()" data-options="iconCls:\'icon-add\'">Append</div>
                  <div onclick="TreeAction.removeit()" data-options="iconCls:\'icon-remove\'">Remove</div>
                  <div onclick="TreeAction.rename()" data-options="iconCls:\'icon-edit\'">rename</div>
                </div>';

    return '<div class="scroller">' . $bookMenu . $editor . $treeMenue . '</div>';

  }

}



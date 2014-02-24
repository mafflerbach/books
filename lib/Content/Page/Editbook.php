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



    return '<div class="scroller">' . $bookMenu . $editor . '</div>';

  }

}



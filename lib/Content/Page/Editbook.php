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


    $screen = '<div id="cc" class="easyui-layout" style="width:100%;height:100%;">
    <div data-options="region:\'west\',title:\'Table of Content\',split:true" style="width:200px;">'.$bookMenu.'</div>
    <div data-options="region:\'center\',title:\'\'">'.$editor.'</div>
    </div>';


    return '<div class="scroller">' . $screen. '</div>';

  }

}



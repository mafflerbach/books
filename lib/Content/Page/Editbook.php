<?php
namespace Content\Page;

class Editbook {
  private $id;
  public function content() {
    $d = new \Xml\Document();
    $c = new \Command\Chain();
    $c->addCommand(new \Book\Command());

    $list = $c->runCommand('getBook', array(':id' => $this->getBook()));
    $layout = $d->appendElement('div', array('class'=>'layout'));
    $center = $layout->appendElement('div', array('class' => "ui-layout-center"));
    $center->appendElement('div', array('class' => 'editor'));
    $west = $layout->appendElement('div', array('class' => 'ui-layout-west'));
    $menu = $west->appendElement('div', array('class' => 'bookmenu'));
    $menu->appendXml('<div class="bookmenu">'.$list->saveXml().'</div>');

    $menu = '
    <ul id="myMenu" class="contextMenu ui-helper-hidden ui-menu ui-widget ui-widget-content ui-corner-all ui-menu-icons">
      <li class="add"><span class="fa fa-plus"></span><a href="#addNode">Add</a></li>
      <li class="delete"><span class="fa fa-minus"></span><a href="#deleteBook">Delete</a></li>
      <li class="rename"><span class="fa fa-pencil"></span><a href="#renameBook">Rename</a></li>
    </ul>';

    $script = $d->appendElement('script', array('type' => 'text/javascript'), "$('.layout').layout();initBooktree()");


    return $layout->saveXml().$script->saveXml() .$menu ;
  }

  public function setBook($id) {
    $this->id = $id;
  }

  public function getBook() {
    return $this->id;
  }

}



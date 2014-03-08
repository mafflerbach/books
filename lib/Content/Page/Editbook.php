<?php
namespace Content\Page;

class Editbook {
  public function content() {
    $d = new \Xml\Document();
    $c = new \Command\Chain();
    $c->addCommand(new \Book\Command());
    $list = $c->runCommand('getBook', array(':id' => 1));

    $layout = $d->appendElement('div', array('class'=>'layout'));

    $center = $layout->appendElement('div', array('class' => "ui-layout-center"));
    $center->appendElement('div', array('class' => 'editor'));
    $west = $layout->appendElement('div', array('class' => 'ui-layout-west'));
    $menu = $west->appendElement('div', array('class' => 'bookmenu'));
    $menu->appendXml('<div class="bookmenu">'.$list->saveXml().'</div>');

    $sc = new \Xml\Document();
    $script = $d->appendElement('script', array('type' => 'text/javascript'), "$('.layout').layout({ applyDemoStyles: true });initBooktree()");


    return $layout->saveXml().$script->saveXml() ;
  }

}



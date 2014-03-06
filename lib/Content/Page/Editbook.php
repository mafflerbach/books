<?php
namespace Content\Page;

class Editbook {
  public function content() {
    $d = new \Xml\Document();
    $layout = $d->appendElement('div', array('id'=>'cc','class' => 'easyui-layout', 'style'=>'width:100%;height:100%;'));
    $west = $layout->appendElement('div', array('data-options' => "region:'west',title:'Table of Content',split:true", 'style'=>"width:200px;"));
    $menu = $west->appendElement('div', array('class' => 'bookmenu'));
    $menu->appendElement('ul', array('class' => 'easyui-tree', 'id'=>'tt'));

    $center = $layout->appendElement('div', array('data-options' => "region:'center',title:''"));
    $center->appendElement('div', array('class' => 'editor'));

    return $layout->saveXml();
  }

}



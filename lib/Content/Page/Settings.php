<?php
namespace Content\Page;

class Settings {
  public function content() {
    $d = new \Xml\Document();
    $div = $d->appendElement('div', array('id'=>'userData'));
    $div->appendElement('h2', array(),'Change Userdata');
    $this->userData($div);

    $div = $d->appendElement('div', array('id'=>'changePassword'));
    $div->appendElement('h2', array(),'Change Passsword');
    $this->changePassword($div);

    print($d->saveXML());
  }

  private function generateSettings() {

  }

  private function userData($d){
    $form = $d->appendElement('form');
    $this->addFormField($form, 'name', array('id'=>'name', 'name'=>'name', 'type'=>'text'), 'Name');
    $this->addFormField($form, 'surname', array('id'=>'surname', 'name'=>'surname', 'type'=>'text'), 'Surname');
    $this->addFormField($form, 'email', array('id'=>'email', 'name'=>'email', 'type'=>'text'), 'E-Mail');
    $form->appendElement('label', array('for' =>'about'), 'About me');
    $form->appendElement('textarea', array('id'=>'about', 'name' =>'content'));
    $form->appendElement('br');
    $form->appendElement('button', array('id'=>'sendUserdata', 'name' =>'userdata'), 'save');
  }

  private function changePassword($d) {
    $form = $d->appendElement('form');
    $this->addFormField($form, 'email', array('id'=>'password', 'name'=>'password', 'type'=>'password'), 'Password');
    $this->addFormField($form, 'email', array('id'=>'repassword', 'name'=>'repassword', 'type'=>'password'), 're Password');
    $form->appendElement('br');
    $form->appendElement('button', array('id'=>'changePass', 'name' =>'changePass'), 'change Password');
  }

  private function addFormField($form, $for, $attrInput, $label) {
    $form->appendElement('label', array('for'=> $for), $label);
    $form->appendElement('input', $attrInput);
  }

}



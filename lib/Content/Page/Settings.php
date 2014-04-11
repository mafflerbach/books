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

    $div = $d->appendElement('div', array('id'=>'generateSettings'));
    $div->appendElement('h2', array(),'Generator Settings');
    $this->generateSettings($div);

    print($d->saveXML());
  }

  private function generateSettings($d) {
    $form = $d->appendElement('form');
    $form->appendElement('button', array('id'=>'sendSettings', 'name' =>'settings'), 'save');

  }

  private function appendRadiofield($d, $attrInput, $label) {
    //$form->appendElement('label', array('for'=> $for), $label);
    //$form->appendElement('input', $attrInput);
  }

  private function userData($d){
    $id =  $_SESSION['user'];
    $user = new \User\Object($id);

    $form = $d->appendElement('form', array('action' => 'form.php', 'method' => 'post'));
    $this->addFormField($form, 'name', array('id'=>'name', 'name'=>'name', 'type'=>'text', 'value' => $user->name), 'Name');
    $this->addFormField($form, 'surname', array('id'=>'surname', 'name'=>'surname', 'type'=>'text', 'value' => $user->surname), 'Surname');
    $this->addFormField($form, 'email', array('id'=>'email', 'name'=>'email', 'type'=>'text', 'value' => $user->email), 'E-Mail');
    $form->appendElement('label', array('for' =>'about'), 'About me');
    $form->appendElement('textarea', array('id'=>'about', 'name' =>'content'), $user->content);
    $form->appendElement('br');
    $form->appendElement('input', array('type'=>'hidden', 'name'=>'save', 'value'=>'saveUserdata'));
    $form->appendElement('input', array('type'=>'hidden', 'name'=>'page', 'value'=>'settings'));
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

  public function safeForm($arg) {
    print_r($arg);

  }

}



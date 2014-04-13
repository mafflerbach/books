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
    $form->appendElement('input', array('type'=>'hidden', 'name'=>'save', 'value'=>'saveGenerateSetting'));
    $form->appendElement('input', array('type'=>'hidden', 'name'=>'page', 'value'=>'settings'));

  }

  private function languageSettings($d) {
    $d->appendElement();
  }

  private function appendRadiofield($form, $attrInput, $label) {
    $form->appendElement('label', array('for'=> $for), $label);
    $form->appendElement('input', $attrInput);
  }

  private function userData($d){
    $id =  $_SESSION['user'];
    $user = new \User\Object($id);

    $form = $d->appendElement('form', array('action' => 'index.php', 'method' => 'post'));
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
    $form = $d->appendElement('form', array('action' => 'index.php', 'method' => 'post'));
    $this->addFormField($form, 'email', array('id'=>'password', 'name'=>'password', 'type'=>'password'), 'Password');
    $this->addFormField($form, 'email', array('id'=>'repassword', 'name'=>'repassword', 'type'=>'password'), 'Repeat Password');
    $this->addFormField($form, 'email', array('id'=>'oldPassword', 'name'=>'oldPassword', 'type'=>'password'), 'Old Password');
    $form->appendElement('br');
    $form->appendElement('button', array('id'=>'changePass', 'name' =>'changePass'), 'change Password');
    $form->appendElement('input', array('type'=>'hidden', 'name'=>'save', 'value'=>'savePassword'));
    $form->appendElement('input', array('type'=>'hidden', 'name'=>'page', 'value'=>'settings'));

  }

  private function addFormField($form, $for, $attrInput, $label) {
    $form->appendElement('label', array('for'=> $for), $label);
    $form->appendElement('input', $attrInput);
  }

  public function safeForm($arg) {
    if ($arg['save'] == 'saveUserdata') {
      $user = new \User\Object();
      $data = array('content' => $arg['content'],
            'name' => $arg['name'],
            'surname' => $arg['surname'],
            'email' => $arg['email'],

      );
      $user->saveUser($data, $_SESSION['user']);
      $d = new \Xml\Document();
      $div = $d->appendElement('div', array('id'=>'message'));
      $div->appendElement('p', array('id'=>'succsess'), 'Saved');
      print($div->saveXML());
    }


    if ($arg['save'] == 'savePassword') {

      $user = new \User\Object($_SESSION['user']);

      if (function_exists('password_hash')) {
        $hash = password_hash($arg['oldPassword'], PASSWORD_DEFAULT);
      } else {
        $hash = sha1($arg['oldPassword']);
      }


      if ($arg['password'] == $arg['repassword'] && password_verify($arg['oldPassword'], $user->password)) {
        if (function_exists('password_hash')) {
          $newpw= password_hash($arg['password'], PASSWORD_DEFAULT);
        } else {
          $newpw = sha1($arg['password']);
        }
        $data = array('password' => $newpw);
        $user->saveUser($data, $_SESSION['user']);
        $d = new \Xml\Document();
        $div = $d->appendElement('div', array('id'=>'message'));
        $div->appendElement('p', array('id'=>'failure'), 'New password saved');
        print($div->saveXML());
      } else {
        if ($arg['password'] != $arg['repassword']) {
          $d = new \Xml\Document();
          $div = $d->appendElement('div', array('id'=>'message'));
          $div->appendElement('p', array('id'=>'failure'), 'new password does not match');
          print($div->saveXML());
        } else {
          $d = new \Xml\Document();
          $div = $d->appendElement('div', array('id'=>'message'));
          $div->appendElement('p', array('id'=>'failure'), 'Wrong Password');
          print($div->saveXML());
        }
      }
    }


  }

}



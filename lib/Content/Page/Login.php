<?php
namespace Content\Page;

use Xml;

class Login {
  public function content() {
    return $this->getLoginForm() . $this->getRegisterForm();
  }

  private function getRegisterForm() {

    $d = new Xml\Document();
    $section = $d->appendElement('section', array('class' => 'registermain'));
    $form = $section->appendElement('form', array('class' => 'register clearfix'));

    $para = $form->appendElement('p');
    $para->appendElement('input', array('type' => "text",
                                        'id' => "usernamesignup",
                                        'name' => "usernamesignup",
                                        'placeholder' => "Username",
                                        'required' => "required"
                                  )
    );
    $para->appendElement('input', array('type' => "email",
                                        'id' => "emailsignup",
                                        'name' => "emailsignup",
                                        'placeholder' => "your_email@mail.com",
                                        'required' => "required"
                                  )
    );
    $para->appendElement('input', array('type' => "password",
                                        'id' => "passwordsignup",
                                        'name' => "passwordsignup",
                                        'placeholder' => "Your Password",
                                        'required' => "required"
                                  )
    );

    $button = $form->appendElement('button', array('id' => 'signup',
                                                   'name' => 'submit'
                                             )
    );
    $button->appendElement('i', array('class' => 'fa fa-arrow-right'), '');
    $button->appendElement('span', array(), 'Sign up');

    $form->appendElement('p', array('class' => 'emailExist'), 'Email exist');
    $form->appendElement('p', array('class' => 'usernameExist'), 'User Exist');
    $form->appendElement('p', array('class' => 'required'), 'Field required');

    $para2 = $form->appendElement('p', array('class' => 'registerUser'), 'You are a member ?');
    $para2->appendElement('a', array('class' => 'to_login',
                                     'href' => "#tologin"
                               ), 'Go and sign in'
    );

    return $section->saveXML();
  }

  private function getLoginForm() {
    $d = new Xml\Document();
    $section = $d->appendElement('section', array('class' => 'main'));
    $form = $section->appendElement('form', array('class' => 'loginform clearfix', 'action' => '#'));


    $para = $form->appendElement('p');
    $para->appendElement('input', array('type' => "text",
                                        'id' => "loginname",
                                        'name' => "login",
                                        'placeholder' => "Username"
                                  )
    );
    $para->appendElement('input', array('type' => "password",
                                        'id' => "password",
                                        'name' => "password",
                                        'placeholder' => "Password"
                                  )
    );

    $button = $form->appendElement('button', array('id' => 'login'));
    $button->appendElement('i', array('class' => 'fa fa-arrow-right'), '');
    $button->appendElement('span', array(), 'Sign in');

    $form->appendElement('p', array('class' => 'wrongPassword'), 'Your password or username is wrong');
    $para2 = $form->appendElement('p', array('class' => 'newUser'), 'Not a member ?');
    $para2->appendElement('a', array('class' => 'to_register',
                                     'href' => "#tosignUp"
                               ), 'Go and sign in'
    );

    return $section->saveXML();

  }
}



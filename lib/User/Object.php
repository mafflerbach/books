<?php
namespace User;

use Domain;

class Object extends
  Domain\Object {
  protected $_data = array('id' => NULL,
                           'username' => '',
                           'password' => '',
                           'email' => '',
                           'content' => ''
  );
  protected $_tabelName = 'user';
}

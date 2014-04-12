<?php
namespace User;
use Domain;

class Object extends
  Domain\Object {
  protected $_data = array('id' => NULL,
                           'username' => '',
                           'password' => '',
                           'email' => '',
                           'content' => '',
                           'name' => '',
                           'surname' => '',
                           'hash' => ''
                          );
  protected $_tabelName = 'user';
  protected $_db = 'user';

  public function __construct($id = NULL) {
    if ($id != NULL) {
      $this->getUser($id);
    }
  }

  public function getUser ($id) {
    $this->db()->query('select * from user where id=:id', array(':id' => $id));
    $result = $this->db()->fetch();

    $this->_data = array('id' => $result[0]['id'],
                         'username' => $result[0]['username'],
                         'password' => $result[0]['password'],
                         'email' => $result[0]['email'],
                         'content' => $result[0]['content'],
                         'name' => $result[0]['name'],
                         'surname' => $result[0]['surname'],
                         'hash' => $result[0]['hash']
    );
    return $result;
  }

  public function saveUser($args, $id) {

    foreach ($args as $key => $val) {
      $db = $this->db();
      $db->query('update user set '.$key.'=:'.$key.' where id=:id', array(':'.$key => $val, ':id'=> $id));
      $db->execute();
    }
  }

  public function db(\Database\Adapter $instance = null) {
    if ($instance != null) {
      $this->_db = $instance;
    } else {
      $this->_db = \Database\Adapter::getInstance();
    }
    return $this->_db;
  }
}

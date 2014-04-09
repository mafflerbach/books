<?php
namespace User;

class Command implements
  \Command {

  public function onCommand($name, $args) {
    switch ($name) {
      case 'add':
        return $this->addUser($args);
        break;
      case 'getUser';
        return $this->getUser($args);
        break;
      default:
        return 'unknown command';
        break;
    }
  }

  private function addUser($args) {
    $this->db()->query('select * from user where username=:username or email=:email', array(':username' => $args->username,
                                                                                                  ':email' => $args->email
                                                                                            ));
    $result = $this->db()->fetch();

    if (count($result) > 0 && $result[0]['username'] == $args->username) {
      return 'username';
    }
    if (count($result) > 0 && $result[0]['email'] == $args->email) {
      return 'email';
    }

    if (function_exists('password_hash')) {
      $hash = password_hash($args->password, PASSWORD_DEFAULT);
    } else {
      $hash = sha1($args->password);
    }

    $this->db()->query('insert into user (username , password, email, hash) values (:username , :password, :email, :hash)',
                       array(':username' => $args->username,
                             ':password' => $hash,
                             ':email' => $args->email,
                             ':hash' => sha1($args->email)
                       )
    );

    $this->db()->execute();
  }

  public function db(\Database\Adapter $instance = null) {
    if ($instance != null) {
      $this->db = $instance;
    } else {
      $this->db = \Database\Adapter::getInstance();
    }
    return $this->db;
  }
}
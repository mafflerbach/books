<?php

namespace Database;

use Database\Adapter\Exception;

class Adapter {

  private $_config = array();
  private static $_instance = NULL;
  private static $_connected = FALSE;
  /**
   * @var \PDO
   */
  private $_link = NULL;
  /**
   * @var \PDOStatement
   */
  private $_stm = NULL;
  private $_result = NULL;


  public static function getInstance(array $config = array()) {
    if (self::$_instance === NULL) {
      self::$_instance = new self($config);
    }
    return self::$_instance;
  }


  private function __construct(array $config) {

    if (count($config) < 4) {
      throw new Exception('Invalid number of connection parameters');
    }
    $this->_config = $config;
  }

  private function __clone() {
  }


  private function connect() {
    if (self::$_connected === FALSE) {
      list($host, $user, $password, $database) = $this->_config;
      if ((!$this->_link = new \PDO('mysql:host=' . $host . ';charset=UTF8;dbname=' . $database, $user, $password))) {
        throw new Exception('Error connecting to MySQL');
      }
      self::$_connected = TRUE;
      unset($host, $user, $password, $database);
    }
  }

  public function query($query, $param = null) {
    if (is_string($query) and !empty($query)) {
      $this->connect();
      $this->_stm = $this->_link->prepare($query);

      if ($param) {
        foreach ($param as $k => $v) {
          $this->_stm->bindValue($k, $v);
        }
      }
    }
  }

  public function execute () {
    $this->_stm->execute();
  }


  public function fetch() {

    $this->_stm->execute();
    $this->_result = $this->_stm->fetchAll(\PDO::FETCH_ASSOC);

    return $this->_result;
  }
}


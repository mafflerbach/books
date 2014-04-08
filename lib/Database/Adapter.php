<?php

namespace Database;

use Database\Adapter\Exception;

class Adapter {

  private $_config = array();
  private static $db = NULL;
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


  public static function getInstance() {
    if (self::$_instance === NULL) {
      self::$db = self::config();
      self::$_instance = new self(self::$db);
    }
    return self::$_instance;
  }

  public static function config(\Config $config = null) {
    if ($config != null) {
      self::$db = $config->getConfig('database');
    } else {
      $conf = new \Config();
      self::$db = $conf->getConfig('database');
    }
    return self::$db;
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

      if ((!$this->_link = new \PDO('mysql:host=' . self::$db['host'] . ';charset=UTF8;dbname=' . self::$db['database'], self::$db['user'], self::$db['password']))) {
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

  public function execute() {
    $this->_stm->execute();
    $config = new \Config();
    $debug = $config->getConfig('env');

    if ($debug['debug']) {
      $error = $this->_stm->errorInfo();
      if ($error[1] != '') {
        print_r($this->_stm->errorInfo());
      }
    }
  }


  public function fetch() {
    $this->_stm->execute();
    $this->_result = $this->_stm->fetchAll(\PDO::FETCH_ASSOC);
    return $this->_result;
  }
}


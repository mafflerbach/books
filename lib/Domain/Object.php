<?php

namespace Domain;
abstract class Object {

  protected $_data = array();
  public $tableName = '';

  public function __construct(array $data = NULL) {
    if ($data !== NULL) {
      foreach ($data as $property => $value) {
        if (!empty($property)) {
          $this->$property = $value;
        }
      }
    }
  }

  public function __set($property, $value) {

    if (!array_key_exists($property, $this->_data)) {
      throw new ModelObjectException('The specified property is not valid for this domain object.');
    }

    if (strtolower($property) === 'id' AND $this->_data['id'] !== NULL) {
      throw new DomainObjectException('ID for this domain object is immutable.');
    }

    $this->_data[$property] = $value;
  }


  public function __get($property) {

    if (!array_key_exists($property, $this->_data)) {
      throw new DomainObjectException('The property requested is not valid for this domain object.');
    }

    return $this->_data[$property];
  }

  public function __isset($property) {
    return isset($this->_data[$property]);
  }

  public function __unset($property) {
    if (isset($this->_data[$property])) {
      unset($this->_data[$property]);
    }
  }
}
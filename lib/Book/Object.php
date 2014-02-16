<?php
namespace Book;

class Object extends
  \Domain\Object {
  protected $_data = array('id' => NULL,
                           'titel' => ''
  );
  public $tableName = 'book';
}
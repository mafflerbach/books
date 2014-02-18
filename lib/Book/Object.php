<?php
namespace Book;

class Object extends
  \Domain\Object {
  protected $_data = array('id' => NULL,
                           'title' => ''
  );
  public $tableName = 'book';
}
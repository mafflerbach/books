<?php
namespace Chapter;

use Domain;

class Object extends
  Domain\Object {
  protected $_data = array('id' => NULL,
                           'titel' => '',
                           'content' => '',
                           'sort' => '',
                           'bookid' => ''
  );
  protected $_tabelName = 'chapter';
}
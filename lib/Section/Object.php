<?php
namespace Section;

use Domain;

class Object extends
  Domain\Object {
  protected $_data = array('id' => NULL,
    'title' => '',
    'content' => '',
    'chapterid' => ''
  );
  protected $_tabelName = 'sections';
}
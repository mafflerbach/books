<?php

class Chapter extends DomainObjectAbstract {
    protected $_data = array('id' => NULL, 'titel' => '', 'content' => '', 'sort' => '', 'bookid' => '');
    protected $_tabelName = 'chapter';
}
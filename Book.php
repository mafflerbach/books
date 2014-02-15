<?php

class Book extends DomainObjectAbstract {
    protected $_data = array('id' => NULL, 'titel' => '');
    protected $_tabelName = 'book';
}
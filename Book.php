<?php

class Book extends DomainObjectAbstract {
    protected $_data = array('id' => NULL, 'titel' => '');
    public $tableName = 'book';
}
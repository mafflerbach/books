<?php

require_once('autoload.php');
$d = new \Xml\Document();
$c = new \Command\Chain();
$c->addCommand(new \Book\Command());
$list = $c->runCommand('getBook', array(':id' => 1));

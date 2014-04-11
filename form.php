<?php
require_once('autoload.php');

$allowed = array('settings');
if (in_array($_REQUEST['page'], $allowed)) {
    $class = "Content\Page\\".ucfirst($_REQUEST['page']);
    $instance = new $class();
    $instance->safeForm($_REQUEST);
  }
<?php

function __autoload($className) {
  $namespace = str_replace("\\", "/", __NAMESPACE__);
  $className = str_replace("\\", "/", $className);
  $class = "lib/" . (empty($namespace) ? "" : $namespace . "/") . "{$className}.php";

  if (file_exists($class)) {
    include_once($class);
  }
}
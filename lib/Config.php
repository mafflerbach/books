<?php

class Config {
  private $file;
  private $ini;

  public function __construct() {
  }

  public function getConfig($config) {
    $this->ini = parse_ini_file($this->file(), TRUE);
    return $this->ini[$config];
  }

  public function file($configFile = NULL) {
    if ($configFile != NULL) {
      $this->file = 'conf/' . $configFile;
    } else {
      $this->file = 'conf/prod.conf';
    }

    return $this->file;
  }

}
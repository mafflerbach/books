<?php
class Git {
  private $gitDir;
  public function __construct($dir) {
    $this->gitDir = $dir;
  }

  public function commit ($email, $name, $message='') {
    if ($message == '') {
      $message = "'".date('l jS \of F Y h:i:s A')."'";
    }

    if (!in_array('user.email', $this->config())) {
      $email = "'".$email."'";
      $name = "'".$name."'";
      $command = './gitWrapper.sh commit '. $this->gitDir. ' '. $message. ' '. $email. ' ' . $name;
    } else {
      $command = './gitWrapper.sh commit '. $this->gitDir. ' '. $message;
    }
    exec($command, $output);
    return $output;
  }

  public function init() {
    $command = './gitWrapper.sh init ' . $this->gitDir;
    exec($command, $output);
    return $output;
  }

  public function config() {
    $command = './gitWrapper.sh listconf '.$this->gitDir;
    exec($command, $output);
    return $output;
  }

}
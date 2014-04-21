<?php

  class Git {
    private $gitDir;

    public function __construct($dir) {
      $this->gitDir = $dir;
    }

    public function commit($message = '', $email = '', $name = '') {
      if ($message == '') {
        $message = "'" . date('l jS \of F Y h:i:s A') . "'";
      }

      if (!in_array('user.email', $this->config())) {
        $email = "'" . $email . "'";
        $name = "'" . $name . "'";
        $command = './gitWrapper.sh commit ' . $this->gitDir . ' ' . $message . ' ' . $email . ' ' . $name;
      } else {
        $command = './gitWrapper.sh commit ' . $this->gitDir . ' ' . $message;
      }
      exec($command, $output);

      return $output;
    }

    public function init() {
      $command = './gitWrapper.sh init ' . $this->gitDir;
      exec($command, $output);

      return $output;
    }

    public function log() {
      $command = './gitWrapper.sh log ' . $this->gitDir;
      exec($command, $output);

      return $output;
    }

    public function config() {
      $command = './gitWrapper.sh listconf ' . $this->gitDir;
      exec($command, $output);

      return $output;
    }

    public function revert($revision) {
      $command = './gitWrapper.sh revert ' . $this->gitDir .' ' . $revision;
      exec($command, $output);

      return $output;
    }

    public function diff($rev1, $rev2) {
      $command = './gitWrapper.sh revert ' . $this->gitDir .' ' . $revision;
      $output = shell_exec($command);

      return $output;
    }

    private function diffOutput ($output) {

    }


  }
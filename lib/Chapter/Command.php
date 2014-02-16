<?php
namespace Chapter;

class Command implements
  \Command {
  public function onCommand($name, $args) {
    switch ($name) {
      case 'create':
        break;
      default:
        print('unknown command');
        break;
    }

  }
}
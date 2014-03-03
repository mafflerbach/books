<?php
namespace Content\Page;

class Help {
  public function content() {
    print('help');
  }

  private function getUserDir() {
    print_r($_SESSION);
  }

}



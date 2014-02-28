<?php
namespace Content\Page;

class Listbooks {
  public function content() {

  }

  private function getList($type) {
    $db = \Database\Adapter::getInstance();
    $db->query('select * from book');
    $db->execute();
    $result = $db->fetch();
    $html = '';
    foreach ($result as $book) {
      $html .= '<li class="'.$type.'" id="book_' . $book['id'] . '"><a class=""><span class="fa fa-file-text-o"></span>' . $book['title'] . '</a></li>';
    }
    return $html;
  }
}

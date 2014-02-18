<?php
namespace Book;

class Command implements
  \Command {

  private $db;

  public function onCommand($name, $args) {
    switch ($name) {
      case 'delete' :
        $this->delete($args);
        break;
      case 'getBook' :
        return $this->getBook($args);
        break;
      case 'getChapter' :
        return $this->getChapter($args);
        break;
      case 'rename' :
        return $this->rename($args);
        break;
      case 'addBook' :
        return $this->addBook($args);
        break;
      default:
        print('unknown command');
        break;

    }
  }

  private function addBook(\Domain\Object $args) {
    $this->db()->query('insert into ' . $args->tableName . ' (title)values("' . $args->title . '")');
    $this->db()->execute();
  }

  private function delete(\Domain\Object $args) {
    $this->db()->query('delete from book where id =:id', array(':id' => $args->id));
    $this->db()->execute();
    $this->db()->query('delete from chapter where bookid =:bookId',array(':bookId' => $args->id));
    $this->db()->execute();
  }

  private function getChapter($args) {

    $this->db()->query('select * from chapter where bookId = :bookid and id = :id', $args);
    $result = $this->db()->fetch();
    $json = json_encode($result[0]);

    return $json;
  }

  private function rename($args) {

    $this->db()->query('update book set title = :title where id = :id', $args);
    $this->db()->execute();
  }

  private function getBook() {
    $this->db()->query('select * from book');
    $books = $this->db()->fetch();
    $treeArray = array();

    foreach ($books as $book) {
      $this->db()->query('select * from chapter where bookId = :id order by sort', array(':id' => $book['id']));
      $chapters = $this->db()->fetch();

      $bookTmp = array(
        'id' => $book['id'],
        'text' => $book['title'],
        'book' => $book['id'],
      );
      foreach ($chapters as $chapter) {
        $chapterTmp = array(
          'id' => $chapter['id'],
          'text' => $chapter['title'],
          'chapter' => $chapter['id'],
        );
        $bookTmp['children'][] = $chapterTmp;
      }
      $treeArray[] = $bookTmp;
    }

    return json_encode($treeArray);
  }

  public function db(\Database\Adapter $instance = null) {
    if ($instance != null) {
      $this->db = $instance;
    } else {
      $this->db = \Database\Adapter::getInstance();
    }
    return $this->db;
  }

}
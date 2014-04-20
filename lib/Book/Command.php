<?php
namespace Book;

class Command implements
  \Command {

  private $db;

  public function onCommand($name, $args) {
    switch ($name) {
      case 'remove' :
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
      case 'add' :
        return $this->addBook($args);
        break;
      case 'bookList' :
        return $this->bookList($args);
        break;
      default:
        return 'unknown command';
        break;

    }
  }

  private function bookList($args) {
    $this->db()->query('select * from book where userId=:userId', array(':userId' => $args[':userId']));
    return $this->db()->fetch();

  }

  private function addBook($args) {

    $this->db()->query('insert into book (title, userid)values(:title, :userid)', array(':title' => $args['title'], ':userid' =>$args['userid']));
    $this->db()->execute();
  }

  private function delete(\Domain\Object $args) {
    $this->db()->query('delete from book where id =:id', array(':id' => $args->id));
    $this->db()->execute();
    $this->db()->query('delete from chapter where bookid =:bookId', array(':bookId' => $args->id));
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

  private function getBook($args) {
    $this->db()->query('select * from book where id=:id', $args);
    $books = $this->db()->fetch();
    $ul='';

    foreach ($books as $book) {

      $this->db()->query('select * from chapter where bookId = :id order by sort', array(':id' => $book['id']));
      $chapters = $this->db()->fetch();

      $d = new \Xml\Document();
      $ul = $d->appendElement('ul', array('id' => 'treeData'));

      $bookTmp = array(
        'data-id' => $book['id'],
        'data-book' => $book['id'],
        'class' => 'folder',
      );

      $li = $ul->appendElement('li', $bookTmp, $book['title']);

      if (count($chapters) > 0) {
        $ul2 = $li->appendElement('ul');
        foreach ($chapters as $chapter) {
          $chapterTmp = array(
            'data-id' => $chapter['id'],
            'data-chapter' => $chapter['id'],
            'class' => 'folder',
          );

          $li2 = $ul2->appendElement('li', $chapterTmp, $chapter['title']);

          $this->db()->query('select * from sections where chapterid= :id order by sort', array(':id' => $chapter['id']));
          $sections = $this->db()->fetch();
          if (count($sections) > 0) {
            $ul3 = $li2->appendElement('ul');

            foreach ($sections as $section) {
              $sectointmp = array(
                'data-id' => $section['id'],
                'data-section' => $section['id'],
              );
              $li3 = $ul3->appendElement('li', $sectointmp, $section['title']);
            }
          }
        }
      }
    }
    return $ul;
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
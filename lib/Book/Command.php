<?php
namespace Book;

class Command implements
  \Command {
  public function onCommand($name, $args) {
    switch ($name) {
      case 'create':
          $this->create($args);
        break;
      case 'delete' :
          $this->delete($args);
        break;
      case 'getBook' :
          return $this->getBook($args);
        break;
      default:
          print('unknown command');
        break;
    }
  }

  private function create(DomainObjectAbstract $args) {
    $db = \Database\Adapter::getInstance(array('localhost',
                                          'root',
                                          '',
                                          'books'
                                    )
    );
    $db->query('insert into ' . $args->tableName . ' (titel)values("' . $args->titel . '")');
  }

  private function delete(DomainObjectAbstract $args) {
    $db = \Database\Adapter::getInstance(array('localhost',
                                          'root',
                                          '',
                                          'books'
                                    )
    );
    $db->query('delete from ' . $args->tableName . ' where id = ' . $args->id . '');
    $db->query('delete from chapter where bookid = ' . $args->id . '');
  }

  private function getBook() {

    $db = \Database\Adapter::getInstance(array('localhost',
                                               'root',
                                               '',
                                               'books'
                                         )
    );
    $db->query('select * from book');
    $books = $db->fetch();

    $treeArray = array();


    foreach ($books as $book) {
      $db->query('select * from chapter where bookid = :id order by sort', array(':id' => $book['id']));
      $chapters= $db->fetch();

      $bookTmp = array(
        'id' => $book['id'],
        'text' => $book['titel'],
        'book' => $book['id'],
      );
      foreach ($chapters as $chapter) {
        $chapterTmp = array(
          'id' => $chapter['id'],
          'text' => $chapter['titel'],
          'chapter' => $chapter['id'],
        );
        $bookTmp['children'][] = $chapterTmp;
      }
      $treeArray[] = $bookTmp;
    }

    return json_encode($treeArray);
  }

}
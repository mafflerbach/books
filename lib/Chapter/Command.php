<?php
namespace Chapter;

class Command implements
  \Command {

  private $db;

  public function onCommand($name, $args) {
    switch ($name) {
      case 'saveChapter':
        $this->saveChapter($args);
        break;
      case 'rename':
        $this->rename($args);
        break;
      case 'add':
        $this->addChapter($args);
        break;
      case 'remove':
        $this->removeChapter($args);
        break;
      case 'update':
        $this->updateChapter($args);
        break;
      default:
        return 'unknown command';
        break;
    }
  }

  private function saveChapter($args) {

    $this->db()->query('update chapter set content=:content where id=:id',
                       array(':id' => $args->id,
                             ':content' => $args->content
                       )
    );

    $this->db()->execute();
  }

  private function updateChapter($args) {
    $s = 1;
    foreach ($args['order'] as $node) {
      $this->db()->query('update chapter set sort=:sort where id=:id',
                         array(':sort' => $s,
                               ':id' => $node
                         )
      );
      $this->db()->execute();
      $s++;
    }
  }

  private function addChapter($args) {

    $this->db()->query('insert into chapter (title, bookid) values (:title, :bookid)',
                       array(':bookid' => $args->bookid,
                             ':title' => $args->title
                       )
    );

    $this->db()->execute();
  }

  private function removeChapter($args) {
    $this->db()->query('delete from chapter where id = :id',
                       array(':id' => $args->id
                       )
    );
    $this->db()->execute();
    $this->db()->query('delete from sections where chapterid=:id',
                       array(':id' => $args->id
                       )
    );

    $this->db()->execute();
  }

  private function rename($args) {
    $this->db()->query('update chapter set title = :title where id = :id', $args);
    $this->db()->execute();
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
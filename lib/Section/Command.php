<?php
namespace Section;


class Command implements
  \Command {

  public function onCommand($name, $args) {
    switch ($name) {
      case 'addSection':
        $this->addSection($args);
        break;
      case 'saveSection':
        $this->saveSection($args);
        break;
      case 'getSection':
        return $this->getSection($args);
        break;
      default:
        return 'unknown command';
        break;
    }
  }


  private function addSection($args) {
    $this->db()->query('insert into sections (title, chapterid) values (:title, :chapterid)',
      array(':chapterid' => $args->chapterid,
        ':title' => $args->title
      )
    );
    $this->db()->execute();
  }

  private function getSection($args) {
    $this->db()->query('select * from sections where id=:id',
      array(':id' => $args->id,
      )
    );

    $this->db()->execute();
    $res = $this->db()->fetch();
    return json_encode($res[0]);
  }

  private function saveSection($args) {
    $this->db()->query('update sections set content=:content where id=:id',
      array(':id' => $args->id,
        ':content' => $args->content
      )
    );

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

<?php
namespace Chapter;

class Command implements
  \Command {
  public function onCommand($name, $args) {
    switch ($name) {
      case 'create':
        break;
      case 'saveChapter':
        $this->saveChapter($args);
        break;
      default:
        print('unknown command');
        break;
    }
  }

  private function saveChapter($args) {
    $db = \Database\Adapter::getInstance(array('localhost',
                                               'root',
        'root',
                                               'books'
                                         )
    );


    $db->query('update chapter set content=:content where id=:id',
               array(':id' => $args->id,
                     ':content' => $args->content
               )
    );

    $db->execute();

  }

}
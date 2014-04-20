<?php
  namespace Content\Page;

  class Versioning {
    public function content() {
      $this->getFileList();

      print('<script type="text/javascript" src="js/versioning.js"></script>');

    }

    private function getUserDir() {
      $this->db()->query('select * from user where id = :id', array(':id' => $_SESSION['user']));
      $result = $this->db()->fetch();

      return 'tmp/' . $result[0]['hash'] . '/git';
    }

    public function db(\Database\Adapter $instance = NULL) {
      if ($instance != NULL) {
        $this->db = $instance;
      } else {
        $this->db = \Database\Adapter::getInstance();
      }

      return $this->db;
    }

    private function getFileList() {
      $bookCmd = new \Book\Command();
      $bookList = $bookCmd->onCommand('bookList', array(':userId' => $_SESSION['user']));

      $d = new \Xml\Document();
      $ul = $d->appendElement('ul', array('class' => 'filelist'));

      foreach ($bookList as $book) {

        $li = $ul->appendElement('li', array('class' => 'item'), $book['title']);
        $ul2 = $li->appendElement('ul', array('class' => 'item'));
        $li2 = $ul2->appendElement('li', array('class' => 'item'));
        $li2->appendElement('a', array('class' => 'block','data-bookId' => $book['id'], 'data-commit' => 'commit', 'href' => "#"), 'commit');

        $git = new \Git($this->getUserDir() . '/' . str_replace(' ', '_', $book['title']));
        $log = $git->log();

        $li3 = $ul2->appendElement('li', array('class' => 'version'));
        for ($i = 0; $i < count($log); $i++) {
          $splitLine = explode('^', $log[$i]);
          $li3->appendElement('span', array('style' => 'color:darkblue'), str_replace("'", '', $splitLine[1]));
          $li3->appendElement('span', array('style' => 'color:darkred'), $splitLine[2]);

          $li3->appendElement('a',
            array('style' => 'color:darkbrown', 'href' => '#', 'data-bookId' => $book['id'], 'data-revert' => $splitLine[0]),
            'revert to this version');

          $li3->appendElement('br');
        }
      }
      print($ul->saveXML());

    }
  }

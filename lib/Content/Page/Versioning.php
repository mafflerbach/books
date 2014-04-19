<?php
  namespace Content\Page;

  class Versioning {
    public function content() {
      $this->getFileList();

    }

    private function getUserDir() {
      $this->db()->query('select * from user where id = :id', array(':id' => $_SESSION['user']));
      $result = $this->db()->fetch();
      return 'tmp/' . $result[0]['hash'] . '/git';
    }

    public function db(\Database\Adapter $instance = null) {
      if ($instance != null) {
        $this->db = $instance;
      } else {
        $this->db = \Database\Adapter::getInstance();
      }
      return $this->db;
    }

    private function getFileList() {
      $d = new \Xml\Document();
      $ul = $d->appendElement('ul', array('class' => 'filelist'));
      $path = $this->getUserDir();

      foreach (new \DirectoryIterator($path) as $fileInfo) {
        if ($fileInfo->isDot()) {
          continue;
        }

        $li = $ul->appendElement('li', array('class' => 'item'), $fileInfo->getFilename());
        $ul2 = $li->appendElement('ul', array('class' => 'item'));
        $li2 = $ul2->appendElement('li', array('class' => 'item'));
        $li2->appendElement('a', array('class'=>'block', 'data-command' => 'commit', 'href'=>"#"), 'commit');
        $li2->appendElement('a', array('class'=>'block','data-command' => 'showVersion', 'href'=>"#"), 'show Versions');

        $git = new \Git($this->getUserDir().'/'.$fileInfo->getFilename());
        $log = $git->log();


        $li3 = $ul2->appendElement('li', array('class' => 'version' ));
        for ($i = 0; $i < count($log); $i++) {
          $splitLine = explode('^', $log[$i]);
          $li3->appendElement('span', array('style'=>'color:darkblue'), str_replace("'", '', $splitLine[0]));
          $li3->appendElement('span', array('style'=>'color:darkred'), $splitLine[1]);
          $li3->appendElement('br');
        }

      }

      print($ul->saveXML());
    }
  }
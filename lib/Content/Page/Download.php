<?php
namespace Content\Page;

class Download {
  public function content() {
    $this->getFileList();
  }

  private function getUserDir() {
    $this->db()->query('select * from user where id = :id', array(':id' => $_SESSION['user']));
    $result = $this->db()->fetch();
    return 'tmp/' . $result[0]['hash'] . '/gen';
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
    print($path);
    foreach (new \DirectoryIterator($path) as $fileInfo) {
      if ($fileInfo->isDot()) {
        continue;
      }
      if ($fileInfo->isDir()) {
        $newPath = $fileInfo->getRealPath();
        $li = $ul->appendElement('li');
        $li->appendElement('span', array('class' => 'head'), $fileInfo->getFilename());
        $ul2 = $li->appendElement('ul', array('class' => 'lev2'));
        foreach (new \DirectoryIterator($newPath) as $fileInfo2) {
          if ($fileInfo2->isDot()) {
            continue;
          }
          $elem = $ul2->appendElement('li', array('class' => 'item'));
          $link = $path . '/' . $fileInfo->getFilename() . '/' . $fileInfo2->getFilename();
          $elem->appendElement('a', array('href' => $link), $fileInfo2->getFilename());
          $elem->appendElement('span', array('class' => 'date'), date('l jS \of F Y h:i:s A', $fileInfo2->getMTime()));
        }
      } else {
        $ul->appendElement('li', array('class' => 'item'), $fileInfo->getFilename());
      }
    }

    print($ul->saveXML());
  }
}



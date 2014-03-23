<?php
namespace Content\Page;

class Upload {
  public function content() {
    $tree = '<div id="tree">'.$this->getDir().'</div>';
    $menu = '
    <ul id="myMenu" class="contextMenu ui-helper-hidden ui-menu ui-widget ui-widget-content ui-corner-all ui-menu-icons">
      <li class="add"><span class="fa fa-plus"></span><a href="#addNode">Add</a></li>
      <li class="delete"><span class="fa fa-minus"></span><a href="#deleteBode">Delete</a></li>
    </ul>';

    $js = '
    <script type="text/javascript">
        initFiletree()
    </script>';

    print($tree . $menu. $js);
  }

  private function getDir() {
    $path = 'tmp/' . $_SESSION['hash'] . '/images';
    if (!file_exists($path)) {
      mkdir($path, 0777, true);
    }
    $objects = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path), \RecursiveIteratorIterator::SELF_FIRST);
    $dom = new \Xml\Document();
    $list = $dom->appendElement("ul", array('id' => 'treeData'));
    $dom->appendChild($list);
    $node = $list;
    $depth = 0;
    foreach ($objects as $name => $object) {
      if ($object->getFilename() == '.' || $object->getFilename() == '..') {
        continue;
      }
      if ($objects->getDepth() == $depth) {
        $li = $dom->appendElement('li', array('data-path' => $object->getPathname()));
        $li->appendElement('span', array(), $object->getFilename());
        $node->appendChild($li);
      } elseif ($objects->getDepth() > $depth) {
        $li = $node->lastChild;
        $ul = $dom->appendElement('ul');
        $li->setAttribute('class', 'folder');
        $li->setAttribute('data-folder', 'true');
        $li->appendChild($ul);
        $li2 = $ul->appendChild($dom->appendElement('li', array('data-path' => $object->getPathname())));
        $li2->appendElement('span', array(), $object->getFilename());
        $node = $ul;
      } else {
        $difference = $depth - $objects->getDepth();
        for ($i = 0; $i < $difference; $difference--) {
          $node = $node->parentNode->parentNode;
        }
        $li = $dom->appendElement('li', array('data-path' => $object->getPathname()));
        $li->appendElement('span', array(), $object->getFilename());
        $node->appendChild($li);
      }
      $depth = $objects->getDepth();
    }
    return $list->saveXml();
  }
}

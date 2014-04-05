<?php
namespace Content\Page;

class Upload {
  public function content() {
    $tree = '<div id="imagetree">'.$this->getDir().'</div>';
    $menu = '
    <ul id="myMenu" class="contextMenu ui-helper-hidden ui-menu ui-widget ui-widget-content ui-corner-all ui-menu-icons">
      <li class="add" data-type="add"><span class="fa fa-plus"></span><a href="#addNode" data-type="add">Add</a></li>
      <li class="delete" data-type="delete"><span class="fa fa-minus"></span><a href="#deleteNode">Delete</a></li>
    </ul>';


    $fileUpload = '<div class="fileupload"><div class="uploadDialog">
    <form id="upload" method="post" action="upload.php" enctype="multipart/form-data"> <div id="drop">
    Drop Here
    <a>Browse</a>
    <input type="file" name="upl" multiple />
    <input type="hidden" id="path" name="path" value=""/>
    </div>
    <ul></ul>
    </form>
    </div></div>';

    $js = '
    <script type="text/javascript">
        initFiletree();
        test();
    </script>';




    print($fileUpload . $tree .$menu .$js );
  }

  private function getDir() {
    $path = 'tmp/' . $_SESSION['hash'].'/images';
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

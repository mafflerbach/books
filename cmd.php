<?php

require_once('autoload.php');
header('Content-Type: text/html; charset=utf-8');

if (isset($_GET['cmd']) && $_GET['cmd'] == 'getTree') {
  $c = new Command\Chain();
  $c->addCommand(new Book\Command());
  print($c->runCommand('getBook', Null));
}

if (isset($_POST['cmd']) && $_POST['cmd'] == 'getChapter') {
  $c = new Command\Chain();
  $c->addCommand(new Book\Command());

  print($c->runCommand('getChapter', array(':id' => $_POST['chapterId'],
      ':bookid' => $_POST['bookId']
    )
  ));
}


if (isset($_POST['cmd']) && $_POST['cmd'] == 'rename') {
  $c = new Command\Chain();

  if ($_POST['type'] == 'chapter') {
    $c->addCommand(new Chapter\Command());
  }

  if ($_POST['type'] == 'book') {
    $c->addCommand(new Book\Command());
  }

  $c->runCommand('rename', array(':id' => $_POST['id'],
      ':title' => $_POST['text']
    )
  );
}

if (isset($_POST['cmd']) && $_POST['cmd'] == 'addChapter') {
  $c = new Command\Chain();
  $c->addCommand(new Chapter\Command());

  $chapter = new \Chapter\Object();
  $chapter->bookid = $_POST['id'];
  $chapter->title = $_POST['text'];

  $c->runCommand('addChapter', $chapter);

}

if (isset($_POST['cmd']) && $_POST['cmd'] == 'addSection') {
  $c = new Command\Chain();
  $c->addCommand(new Section\Command());

  $chapter = new \Section\Object();
  $chapter->chapterid = $_POST['id'];
  $chapter->title = $_POST['text'];

  $c->runCommand('addSection', $chapter);

}

if (isset($_POST['cmd']) && $_POST['cmd'] == 'removeChapter') {
  $c = new Command\Chain();
  $c->addCommand(new Chapter\Command());

  $chapter = new \Chapter\Object();
  $chapter->id = $_POST['id'];

  $c->runCommand('removeChapter', $chapter);
}

if (isset($_POST['cmd']) && $_POST['cmd'] == 'removeBook') {
  $c = new Command\Chain();
  $c->addCommand(new Book\Command());

  $book = new \Book\Object();
  $book->id = $_POST['id'];

  $c->runCommand('delete', $book);
}

if (isset($_POST['cmd']) && $_POST['cmd'] == 'addBook') {
  $c = new Command\Chain();
  $c->addCommand(new Book\Command());

  $book = new \Book\Object();
  $book->title = $_POST['text'];

  $c->runCommand('addBook', $book);
}


if (isset($_POST['cmd']) && $_POST['cmd'] == 'saveChapter') {
  $c = new Command\Chain();
  $c->addCommand(new Chapter\Command());

  $chapter = new \Chapter\Object();
  $chapter->id = $_POST['id'];
  $chapter->content = $_POST['content'];

  $c->runCommand('saveChapter', $chapter);
}

if (isset($_POST['cmd']) && $_POST['cmd'] == 'saveSection') {
  $c = new Command\Chain();
  $c->addCommand(new Section\Command());

  $section = new \Section\Object();
  $section->id = $_POST['id'];
  $section->content = $_POST['content'];

  $c->runCommand('saveSection', $section);

}

if (isset($_POST['cmd']) && $_POST['cmd'] == 'getSection') {
  $c = new Command\Chain();
  $c->addCommand(new Section\Command());

  $section = new \Section\Object();
  $section->id = $_POST['id'];
  print($c->runCommand('getSection', $section));
}


if (isset($_POST['cmd']) && $_POST['cmd'] == 'export') {
  export($_POST['bookId']);
}


if (isset($_POST['cmd']) && $_POST['cmd'] == 'updateSection') {
  $c = new Command\Chain();
  $c->addCommand(new Section\Command());

  $order =
  array ('order' => json_decode($_POST['node']));
  $c->runCommand('updateSection', $order);
}

if (isset($_POST['cmd']) && $_POST['cmd'] == 'updateChapter') {

  $c = new Command\Chain();
  $c->addCommand(new Chapter\Command());

  print_r($_POST);
  $order =
  array ('order' => json_decode($_POST['node']));
  $c->runCommand('updateChapter', $order);
}


function export($bookId) {
  $db = \Database\Adapter::getInstance();

  $db->query('select * from book where id=:id', array(':id' => $bookId));
  $db->execute();
  $bookResult = $db->fetch();

  $db->query('select * from chapter where bookid=:bookid', array(':bookid' => $bookId));
  $db->execute();
  $result = $db->fetch();


  $html = '';
  foreach ($result as $chapter) {
    $html .= '<div><h2>' . $chapter['title'] . '</h2>';

    $db->query('select * from sections where chapterid=:chapterid', array(':chapterid' => $chapter['id']));
    $db->execute();
    $sections = $db->fetch();

    foreach ($sections as $seciton) {
      $html .= '<div><h3>' . $seciton['title'] . '</h3>';
      $html .= $seciton['content'] . '</div>';

    }

    $html .= '</div>';

  }

  $str = '
<html>
  <head>
    <title>' . $bookResult[0]['title'] . '</title>
  </head>
  <body>' . $html . '</body>
</html>';

  file_put_contents('tmp/test.html', $str);

  $doc = new DOMDocument();
  $xsl = new XSLTProcessor();

  $doc->load('templates/docbook2.xsl');
  $xsl->importStyleSheet($doc);

  $doc->loadHTML($str);
  file_put_contents('tmp/test.docbook', $xsl->transformToXML($doc));

  $doc = new DOMDocument();
  $xsl = new XSLTProcessor();

  $doc->load('vendor/docbook/epub3/chunk.xsl');
  $xsl->importStyleSheet($doc);

  $doc->loadXml(file_get_contents('tmp/test.docbook'));
  file_put_contents('tmp/ebook/test.xml', $xsl->transformToXML($doc));

}
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
  $chapter->id = $_POST['chapterId'];
  $chapter->bookid = $_POST['bookId'];
  $chapter->content = $_POST['content'];

  $c->runCommand('saveChapter', $chapter);

}


if (isset($_POST['cmd']) && $_POST['cmd'] == 'export') {
  export($_POST['bookId']);
}



function export($bookId) {
  $db = \Database\Adapter::getInstance();

  $db->query('select * from book where id=:id', array(':id' => $bookId));
  $db->execute();
  $bookResult= $db->fetch();

  $db->query('select * from chapter where bookid=:bookid', array(':bookid' => $bookId));
  $db->execute();
  $result = $db->fetch();

  $html = '';
  foreach($result as $chapter) {
    $html .= '<div><h2>'.$chapter['title'].'</h2>';
    $html .= $chapter['content'].'</div>';
  }


  $str = '
<html>
  <head>
    <title>'.$bookResult[0]['title'].'</title>
  </head>
  <body>'.$html.'</body>
</html>';

    file_put_contents('tmp/test.html', $str);

  $doc = new DOMDocument();
  $xsl = new XSLTProcessor();

  $doc->load('templates/docbook.xsl');
  $xsl->importStyleSheet($doc);

  $doc->loadHTML($str);
  file_put_contents('tmp/test.docbook', $xsl->transformToXML($doc));


}
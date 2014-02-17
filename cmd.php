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

  print($c->runCommand('getChapter', array(':id'=>$_POST['chapterId'], ':bookid' => $_POST['bookId'])));
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


<?php
session_start();
require_once('autoload.php');
header('Content-Type: text/html; charset=utf-8');

if (isset($_GET['cmd']) && $_GET['cmd'] == 'getTree') {
  $c = new Command\Chain();
  $c->addCommand(new Book\Command());
  print($c->runCommand('getBook', array(':id' => $_GET['id'])));
}

if (isset($_POST['cmd']) && $_POST['cmd'] == 'edit') {
  $bookpage = new Content\Page\Editbook();
  print($bookpage->content());
}

if (isset($_POST['cmd']) && $_POST['cmd'] == 'signup') {

  $c = new Command\Chain();
  $c->addCommand(new User\Command());

  $user = new \User\Object();
  $user->username = $_POST['username'];
  $user->email = $_POST['email'];
  $user->password = $_POST['password'];

  $fields = array();

  if ($_POST['username'] == '') {
    $fields['empty'][] = 'username';
  }
  if ($_POST['email'] == '') {
    $fields['empty'][] = 'email';
  }
  if ($_POST['password'] == '') {
    $fields['empty'][] = 'password';
  }

  if (count($fields) > 0) {
    print(json_encode($fields));
  } else {
    print(json_encode($c->runCommand('add', $user)));
  }
}

if (isset($_POST['cmd']) && $_POST['cmd'] == 'login') {
  $username = $_POST["username"];
  $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

  $db = \Database\Adapter::getInstance();
  $db->query('SELECT * FROM user WHERE username=:username', array(':username' => $username));
  $user = $db->fetch();

  if (password_verify($_POST["password"], $user[0]['password'])) {
    $_SESSION["user"] = $user[0]['id'];
    print('true');
  } else {
    print('false');
  }

}


if (isset($_POST['cmd']) && $_POST['cmd'] == 'getChapter') {
  $c = new Command\Chain();
  $c->addCommand(new Book\Command());

  print($c->runCommand('getChapter', array(':id' => $_POST['chapterId'],
                                           ':bookid' => $_POST['bookId']
                                     )
  ));
}

if (isset($_POST['cmd']) && $_POST['cmd'] == 'getSection') {
  $c = new Command\Chain();
  $c->addCommand(new Section\Command());

  $section = new \Section\Object();
  $section->id = $_POST['id'];
  print($c->runCommand('getSection', $section));
}

if (isset($_POST['cmd']) && $_POST['cmd'] == 'rename') {
  $c = new Command\Chain();

  if ($_POST['type'] == 'chapter') {
    $c->addCommand(new Chapter\Command());
  }

  if ($_POST['type'] == 'book') {
    $c->addCommand(new Book\Command());
  }

  if ($_POST['type'] == 'section') {
    $c->addCommand(new Section\Command());
  }

  $c->runCommand('rename', array(':id' => $_POST['id'],
                                 ':title' => $_POST['text']
                           )
  );
}

if (isset($_POST['cmd']) && $_POST['cmd'] == 'add') {
  $c = new Command\Chain();
  $obj = '';
  if ($_POST['type'] == 'chapter') {
    $c->addCommand(new Chapter\Command());
    $obj = new \Chapter\Object();
    $obj->bookid = $_POST['id'];
    $obj->title = $_POST['text'];
  }

  if ($_POST['type'] == 'book') {
    $c->addCommand(new Book\Command());
    $obj = new \Book\Object();
    $obj->title = $_POST['text'];
  }

  if ($_POST['type'] == 'section') {
    $c->addCommand(new Section\Command());
    $obj = new \Section\Object();
    $obj->chapterid = $_POST['id'];
    $obj->title = $_POST['text'];

  }
  $c->runCommand('add', $obj);
}

if (isset($_POST['cmd']) && $_POST['cmd'] == 'remove') {
  $c = new Command\Chain();

  if ($_POST['type'] == 'chapter') {
    $c->addCommand(new Chapter\Command());
    $obj = new \Chapter\Object();
  }

  if ($_POST['type'] == 'book') {
    $c->addCommand(new Book\Command());
    $obj = new \Book\Object();
  }

  $obj->id = $_POST['id'];
  $c->runCommand('remove', $obj);
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

if (isset($_POST['cmd']) && $_POST['cmd'] == 'export') {
  export($_POST['bookId']);
}


if (isset($_POST['cmd']) && $_POST['cmd'] == 'update') {
  $c = new Command\Chain();

  if ($_POST['type'] == 'section') {
    $c->addCommand(new Section\Command());
  }

  if ($_POST['type'] == 'chapter') {
    $c->addCommand(new Chapter\Command());
  }

  $order = array('order' => json_decode($_POST['node']));
  $c->runCommand('update', $order);
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

  $doc = new DOMDocument();
  $xsl = new XSLTProcessor();

  $doc->load('templates/docbook2.xsl');
  $xsl->importStyleSheet($doc);

  $doc->loadHTML($str);
  file_put_contents('tmp/' . str_replace(' ', '_', $bookResult[0]['title']) . '.xml', $xsl->transformToXML($doc));

}
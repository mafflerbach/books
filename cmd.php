<?php
  session_start();
  require_once('autoload.php');
  header('Content-Type: text/html; charset=utf-8');

  if (isset($_POST['cmd']) && $_POST['cmd'] == 'login') {
    $username = $_POST["username"];

    $db = \Database\Adapter::getInstance();
    $db->query('SELECT * FROM user WHERE username=:username', array(':username' => $username));
    $user = $db->fetch();

    if (function_exists('password_hash')) {
      $hash = password_hash($_POST["password"], PASSWORD_DEFAULT);

      if (password_verify($_POST["password"], $user[0]['password'])) {
        $_SESSION["user"] = $user[0]['id'];
        $_SESSION["hash"] = $user[0]['hash'];
        print('true');
      } else {
        print('false');
      }

    } else {
      $hash = sha1($_POST["password"]);
      if ($hash == $user[0]['password']) {
        $_SESSION["user"] = $user[0]['id'];
        $_SESSION["hash"] = $user[0]['hash'];
        print('true');
      } else {
        print('false');
      }
    }
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

  if (isset($_SESSION['user'])) {
    if (isset($_GET['cmd']) && $_GET['cmd'] == 'getTree') {
      $c = new Command\Chain();
      $c->addCommand(new Book\Command());
      print($c->runCommand('getBook', array(':id' => $_POST['id'])));
    }

    if (isset($_POST['cmd']) && $_POST['cmd'] == 'edit') {
      $bookpage = new Content\Page\Editbook();
      $bookpage->setBook($_POST['bookId']);
      print($bookpage->content());
    }

    if (isset($_POST['cmd']) && $_POST['cmd'] == 'getChapter') {
      $c = new Command\Chain();
      $c->addCommand(new Book\Command());

      print($c->runCommand('getChapter',
        array(
          ':id'     => $_POST['chapterId'],
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

      $c->runCommand('rename',
        array(
          ':id'    => $_POST['id'],
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
        $obj = array('title' => $_POST['text'], 'userid' => $_SESSION['user']);
      }

      if ($_POST['type'] == 'section') {
        $c->addCommand(new Section\Command());
        $obj = new \Section\Object();
        $obj->chapterid = $_POST['id'];
        $obj->title = $_POST['text'];
      }

      if ($_POST['type'] == 'folder') {
        $path = $_POST['dirpath'] . "/" . $_POST['name'];
        if (is_file($_POST['dirpath'])) {
          $pathArr = explode('/', $_POST['dirpath']);
          array_pop($pathArr);
          $path = implode('/', $pathArr) . '/' . $_POST['name'];
        }

        mkdir($path, 0777);
      }

      $c->runCommand('add', $obj);
    }

    if (isset($_POST['cmd']) && $_POST['cmd'] == 'remove') {
      $c = new Command\Chain();

      if ($_POST['type'] == 'folder') {
        $path = '/vagrant/project/' . $_POST['dirpath'];
        system('/bin/rm -rf ' . escapeshellarg($path));
      }

      if ($_POST['type'] == 'chapter') {
        $c->addCommand(new Chapter\Command());
        $obj = new \Chapter\Object();
      }

      if ($_POST['type'] == 'section') {
        $c->addCommand(new Section\Command());
        $obj = new \Section\Object();
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

    if (isset($_POST['cmd']) && $_POST['cmd'] == 'addDir') {
      $_POST['dirname'] = $name;
      $_POST['parent'] = $parent;
      $hash = $_SESSION['hash'];
    }

    if (isset($_POST['cmd']) && $_POST['cmd'] == 'getFileTree') {

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

    if (isset($_POST['cmd']) && $_POST['cmd'] == 'getPage') {
      switch ($_POST['page']) {
        case 'download':
          $page = new Content\Page\Download();
          break;
        case 'settings':
          $page = new Content\Page\Settings();
          break;
        case 'help':
          $page = new Content\Page\Help();
          break;
        case 'media':
          $page = new Content\Page\Media();
          break;
        case 'upload':
          $page = new Content\Page\Upload();
          break;

        default:
          $page = new Content\Page\Notfound();

          break;
      }
      print($page->content());
    }
  }

  function export($bookId) {
    $db = \Database\Adapter::getInstance();

    $db->query('select * from book where id=:id', array(':id' => $bookId));
    $db->execute();
    $bookResult = $db->fetch();

    $db->query('select * from chapter where bookid=:bookid', array(':bookid' => $bookId));
    $db->execute();
    $result = $db->fetch();

    $db->query('select * from user where id=:id', array(':id' => $_SESSION['user']));
    $db->execute();
    $user = $db->fetch();

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

    $str
      = '
<html>
  <head>
    <title>' . $bookResult[0]['title'] . '</title>
  </head>
  <body>' . $html . '</body>
</html>';

    print($html);

    $doc = new DOMDocument('1.0', 'UTF-8');
    $xsl = new XSLTProcessor();
    $xsl->setParameter('', 'firstname', $user[0]['name']);
    $xsl->setParameter('', 'surname', $user[0]['surname']);
    $xsl->setParameter('', 'year', date("Y"));
    $doc->load('templates/docbook.xsl');
    $xsl->importStyleSheet($doc);

    $doc->loadHTML($str);
    $bookName = str_replace(' ', '_', $bookResult[0]['title']);
    $path = 'tmp/' . $user[0]['hash'] . '/gen/' . $bookName;

    if (!file_exists($path)) {
      mkdir($path, 0777, TRUE);
    }

    if (file_exists('tmp/' . $user[0]['hash'])) {
      file_put_contents('tmp/' . $user[0]['hash'] . '/gen/' . $bookName . '/' . $bookName . '.xml',
        utf8_decode($xsl->transformToXML($doc)));
    }

    $command = '/vagrant/project/build.sh ' . str_replace(' ', '_', $bookResult[0]['title']) . ' ' . $user[0]['hash'];
    print(shell_exec($command));

  }



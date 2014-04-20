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
        case 'versioning':
          $page = new Content\Page\Versioning();
          break;
        default:
          $page = new Content\Page\Notfound();

          break;
      }
      print($page->content());
    }

    if (isset($_POST['cmd']) && $_POST['cmd'] == 'revert') {

      $bookId = $_POST['bookId'];
      $db = \Database\Adapter::getInstance();

      $db->query('select * from user where id = :id', array(':id' => $_SESSION['user']));
      $user = $db->fetch();

      $db->query('select * from book where id=:id', array(':id' => $bookId));
      $db->execute();
      $bookResult = $db->fetch();
      $bookName = str_replace(' ', '_', $bookResult[0]['title']);

      $dir = 'tmp/' . $user[0]['hash'] . '/git/' . $bookName;

      $message = $_POST['rev'];
      $git = new Git($dir);
      print_r($git->revert($message));
      print_r($git->commit('revert'));
      // do import in to db

    }

    if (isset($_POST['cmd']) && $_POST['cmd'] == 'commit') {

      $bookId = $_POST['bookId'];
      $export = new Export($bookId);
      $export->filesystem();

      $db = \Database\Adapter::getInstance();

      $db->query('select * from user where id = :id', array(':id' => $_SESSION['user']));
      $user = $db->fetch();

      $db->query('select * from book where id=:id', array(':id' => $bookId));
      $db->execute();
      $bookResult = $db->fetch();
      $bookName = str_replace(' ', '_', $bookResult[0]['title']);

      $dir = 'tmp/' . $user[0]['hash'] . '/git/' . $bookName;

      $message = $_POST['message'];
      $git = new Git($dir);
      print_r($git->commit($message));
    }
  }

  function export($bookId) {
    $export = new Export($bookId);
    $export->roundtrip();
  }


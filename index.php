<?php

  session_start();
  require_once('autoload.php');

  if (isset($_SESSION['user'])) {

  } else {
    $page = new Content\Page\Login();
  }

  print(file_get_contents('templates/header.tmpl'));

  print('<div class="mp-pusher" id="mp-pusher">');

  if (isset($_SESSION['user'])) {
    print('<p class="menuList"><a href="#" id="trigger" class="menu-trigger"><span class="fa fa-home"></span>Menu</a></p><div id="clock"><div id="time"></div></div>');

    $allowed = array('settings');
    if (in_array($_REQUEST['page'], $allowed)) {
      $class = "Content\Page\\" . ucfirst($_REQUEST['page']);
      $page = new $class();
      $page->safeForm($_REQUEST);
    }
    $menuBox = new Content\Box\Menu();

    print($menuBox->content());
    if ($page != NULL) {
      print($page->content());
    }
    print('<div class="scroller"></div></div>');

  } else {
    print($page->content());
  }


  print(file_get_contents('templates/footer.tmpl'));


?>


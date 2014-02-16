<?php

require_once('autoload.php');

if (isset($_GET['cmd']) && $_GET['cmd'] == 'getTree') {

  $c = new Command\Chain();
  $c->addCommand(new Book\Command());
  print($c->runCommand('getBook', Null));

}

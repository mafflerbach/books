<?php

require_once('autoload.php');
 ?>

<html>
<head>
  <title>Mozilla Rich Text Editing Demo</title>
  <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
  <link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/smoothness/jquery-ui.css"/>
  <link rel="stylesheet" href="css/jquery-te-1.4.0.css"/>
  <link rel="stylesheet" href="css/custom.css"/>
  <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js"></script>
  <script src="js/jquery-te-1.4.0.js"></script>

  <link rel="stylesheet" type="text/css" href="js/themes/black/easyui.css">
  <link rel="stylesheet" type="text/css" href="js/themes/icon.css">
  <script src="js/jquery.easyui.min.js"></script>

  <meta http-equiv="Content-type" content="text/html; charset=utf-8" />

</head>
<body>
<div>
  <button id="addBook">Buch hinzufügen</button>

  <?php

  function getBookList(){

    $db = \Database\Adapter::getInstance();

    $db->query('select * from book');
    $db->execute();
    $result = $db->fetch();

    $html = '<ul>';
    foreach ($result as $book) {

      $html .= '<li>';
      $html .= '<span>'.$book['title'].'</span>';
      $html .= '<button class="export" value="'.$book['id'].'">export</button>';
      $html .= '</li>';
    }
    $html .= '</ul>';

    return $html;
  }

  print(getBookList());

  ?>
</div>
<div class="bookmenu">
  <ul id="tt" class="easyui-tree"></ul>
</div>
<div class="editor">
</div>

<div id="mm" class="easyui-menu" style="width:120px;">
  <div onclick="TreeAction.append()" data-options="iconCls:'icon-add'">Append</div>
  <div onclick="TreeAction.removeit()" data-options="iconCls:'icon-remove'">Remove</div>
  <div onclick="TreeAction.rename()" data-options="iconCls:'icon-edit'">rename</div>
  <div class="menu-sep"></div>
  <div onclick="TreeAction.expand()">Expand</div>
  <div onclick="TreeAction.collapse()">Collapse</div>
</div>

<script src="js/editor.js"></script>
<script src="js/tree.js"></script>

<script src="js/custom.js"></script>

</body>
</html>



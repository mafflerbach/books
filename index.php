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
<div class="bookmenu">
  <ul id="tt" class="easyui-tree"></ul>
</div>
<div class="editor">
  <textarea id="editor"></textarea>
</div>

<div id="mm" class="easyui-menu" style="width:120px;">
  <div onclick="EditorAction.append()" data-options="iconCls:'icon-add'">Append</div>
  <div onclick="EditorAction.removeit()" data-options="iconCls:'icon-remove'">Remove</div>
  <div onclick="EditorAction.rename()" data-options="iconCls:'icon-edit'">rename</div>
  <div class="menu-sep"></div>
  <div onclick="EditorAction.expand()">Expand</div>
  <div onclick="EditorAction.collapse()">Collapse</div>
</div>

<script src="js/editor.js"></script>
<script src="js/tree.js"></script>

<script src="js/custom.js"></script>

</body>
</html>


<?php

require_once('autoload.php');


//$c = new CommandChain();
//$c->addCommand(new BookCommand());
//$c->runCommand('delete', $b);

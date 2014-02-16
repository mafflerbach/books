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

</head>
<body>
<div class="bookmenu">
  <ul id="tt" class="easyui-tree"></ul>
</div>
<div class="editor">
  <textarea id="editor"></textarea>
</div>

<div id="mm" class="easyui-menu" style="width:120px;">
  <div onclick="append()" data-options="iconCls:'icon-add'">Append</div>
  <div onclick="removeit()" data-options="iconCls:'icon-remove'">Remove</div>
  <div onclick="rename()" data-options="iconCls:'icon-edit'">rename</div>
  <div class="menu-sep"></div>
  <div onclick="expand()">Expand</div>
  <div onclick="collapse()">Collapse</div>
</div>

<script src="custom.js"></script>

</body>
</html>


<?php

require_once('autoload.php');


//$c = new CommandChain();
//$c->addCommand(new BookCommand());
//$c->runCommand('delete', $b);

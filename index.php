<?php
session_start();
require_once('autoload.php');
print(file_get_contents('templates/header.tmpl'));

print('<div class="mp-pusher" id="mp-pusher">');


if (isset($_SESSION['user'])) {
  print('<p class="menuList"><a href="#" id="trigger" class="menu-trigger"><span class="fa fa-home"></span>Menu</a></p>');
  $menuBox = new Content\Box\Menu();
  print($menuBox->content());

} else {
  $login = new Content\Page\Login();
  print($login->content());
}

print('<div class="scroller"></div></div>');
echo  '<div id="mm" class="easyui-menu" style="width:120px;">
                  <div onclick="TreeAction.append()" data-options="iconCls:\'fa-plus\'">Append</div>
                  <div onclick="TreeAction.removeit()" data-options="iconCls:\'fa-minus\'">Remove</div>
                  <div onclick="TreeAction.rename()" data-options="iconCls:\'fa-edit\'">rename</div>
                </div>';
print(file_get_contents('templates/footer.tmpl'));


?>


<?php
require_once('autoload.php');
print(file_get_contents('templates/header.tmpl'));


print('<div class="mp-pusher" id="mp-pusher">');
$menuBox = new Content\Box\Menu();
print($menuBox->content());
$bookpage = new Content\Page\Editbook();
print($bookpage->content());
print('</div>');

print(file_get_contents('templates/footer.tmpl'));


?>


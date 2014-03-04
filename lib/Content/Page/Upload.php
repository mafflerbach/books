<?php
namespace Content\Page;

class Upload {
  public function content() {
    $html = '
<div class="scroller">
    <form action = "upload.php" method="post" enctype="multipart/form-data">
<input type="file" name="imageURL[]" id="imageURL" multiple="" />
<input type="submit" value="submit" name="submit" />
</form></div>';

    print($html);

  }
}



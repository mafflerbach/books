<?php

$files = array();
$fdata = $_FILES['imageURL'];
if (is_array($fdata['name'])) {
  for ($i = 0; $i < count($fdata['name']); ++$i) {
    $files[] = array(
      'name' => $fdata['name'][$i],
      'type' => $fdata['type'][$i],
      'tmp_name' => $fdata['tmp_name'][$i],
      'error' => $fdata['error'][$i],
      'size' => $fdata['size'][$i]
    );
  }
} else {
  $files[] = $fdata;
}

print_r($files );

foreach ($_FILES["pictures"]["error"] as $key => $error) {
  if ($error == UPLOAD_ERR_OK) {
    $tmp_name = $_FILES["pictures"]["tmp_name"][$key];
    $name = $_FILES["pictures"]["name"][$key];
    move_uploaded_file($tmp_name, "data/$name");
  }
}
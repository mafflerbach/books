<?php
session_start();
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

print_r($files);

foreach ($files as $file) {
  if ($file['error'] == UPLOAD_ERR_OK) {
    $tmp_name = $file['tmp_name'];
    $name = $file['name'];
    $hash = $_SESSION['hash'];
    move_uploaded_file($tmp_name, 'tmp/'.$hash.'/images/'.$name);
  }
}
<?php
session_start();
// A list of permitted file extensions
$allowed = array('png', 'jpg', 'gif','zip');

if(isset($_FILES['upl']) && $_FILES['upl']['error'] == 0){

  $extension = pathinfo($_FILES['upl']['name'], PATHINFO_EXTENSION);

  if(!in_array(strtolower($extension), $allowed)){
    echo '{"status":"error"}';
    exit;
  }
  $hash = $_SESSION['hash'];
  $target='tmp/'.$hash.'/images/'.$_FILES['upl']['name'];
  print($target);
  if(move_uploaded_file($_FILES['upl']['tmp_name'], $target)){
    echo '{"status":"success"}';
    exit;
  }
}

echo '{"status":"error"}';
exit;

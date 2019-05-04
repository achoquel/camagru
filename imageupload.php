<?php
session_start();
if ((isset($_SESSION) && !isset($_SESSION['id'])) || strpos($_SERVER['HTTP_REFERER'], 'editor.php?upload') === FALSE)
  header('Location: 404.php');
$target_dir = "img/tmp";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
if(isset($_POST["submit"])) {
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if($check !== false) {
        $uploadOk = 1;
    } else {
        $uploadOk = 0;
    }
}
if (file_exists($target_file)) {
    $uploadOk = 0;
}
if ($_FILES["fileToUpload"]["size"] > 2000000) {
    $uploadOk = 0;
}
if ($_FILES["fileToUpload"]["size"] < 95) {
    $uploadOk = 0;
}
if($imageFileType != "jpg" && $imageFileType != "jpeg") {
    $uploadOk = 0;
}
if ($uploadOk == 0) {
    header('Location: editor.php?upload&error');
} else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
      $file = file_get_contents($target_file);
      unlink($target_file);
      $id = $_SESSION['id'];
      $capture = "img/tmp/capture/$id.$imageFileType";
      if (isset($capture) && file_exists($capture))
        unlink($capture);
      file_put_contents($capture, $file);
      $_SESSION['currentmontage'] = $capture;
      header('Location: editor.php?upload');
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}
?>

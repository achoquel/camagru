<?php
session_start();
if (isset($_SESSION) && isset($_SESSION['id']))
{
$target_dir = "img/tmp/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
// Check if image file is a actual image or fake image
if(isset($_POST["submit"])) {
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if($check !== false) {
        $uploadOk = 1;
    } else {
        header('Location: accountmanager.php?error=af');
        $uploadOk = 0;
    }
}
// Check if file already exists
if (file_exists($target_file)) {
    $uploadOk = 0;
    header('Location: accountmanager.php?error=av');
}
// Check file size
if ($_FILES["fileToUpload"]["size"] > 2000000) {
    $uploadOk = 0;
    header('Location: accountmanager.php?error=as');
}

if ($_FILES["fileToUpload"]["size"] < 95) {
    $uploadOk = 0;
    header('Location: accountmanager.php?error=av');
}
// Allow certain file formats
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
    header('Location: accountmanager.php?error=af');
    $uploadOk = 0;
}
// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    header('Location: accountmanager.php?error=av');
// if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file))
    {
      $id = $_SESSION['id'];
      $file = file_get_contents($target_file);
      unlink($target_file);
      $avatar = "img/avatars/$id.$imageFileType";
      file_put_contents($avatar, $file);
      require('config/database.php');
      require('config/correction.php');
      try {
          $dbh = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
          $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
          $request = $dbh->prepare('UPDATE `users` SET `avatar` = :img WHERE `user_id` = :uid');
          $request->bindParam(':img', $avatar);
          $request->bindParam(':uid', $_SESSION['id']);
          $request->execute();
          $request = null;
          $dbh = null;
          header('Location: accountmanager.php');
          } catch (PDOException $e) {
          print "Erreur !: " . $e->getMessage() . "<br/>";
          die();
        }
    } else {
        header('Location: accountmanager.php?error=av');
    }
}
} else {
  header('Location: 404.php');
}
?>

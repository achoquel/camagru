<?php
session_start();
if (isset($_SESSION) && !isset($_SESSION['id']))
  header('Location: identification.php?login');
$target_dir = "uploads/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
// Check if image file is a actual image or fake image
if(isset($_POST["submit"])) {
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if($check !== false) {
        $uploadOk = 1;
    } else {
        $uploadOk = 0;
        header("Location: editor.php?upload=KO&error=type");
    }
}
// Check if file already exists
if (file_exists($target_file)) {
    $uploadOk = 0;
    header("Location: editor.php?upload=KO&error=duplicate");
}
// Check file size
if ($_FILES["fileToUpload"]["size"] > 500000) {
    $uploadOk = 0;
    header("Location: editor.php?upload=KO&error=size");
    unlink($target_file);
}
// Allow certain file formats
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
    $uploadOk = 0;
    header("Location: editor.php?upload=KO&error=extension");
}
// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 1) {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
      // On charge d'abord les images
      $source = imagecreatefrompng("img/filters/rainbow.png"); // Le logo est la source
      $destination = imagecreatefromjpeg("$target_file"); // La photo est la destination

      // Les fonctions imagesx et imagesy renvoient la largeur et la hauteur d'une image
      $largeur_source = imagesx($source);
      $hauteur_source = imagesy($source);
      $largeur_destination = imagesx($destination);
      $hauteur_destination = imagesy($destination);

      // On veut placer le logo en bas à droite, on calcule les coordonnées où on doit placer le logo sur la photo
      $destination_x = 0;
      $destination_y = 0;

      // On met le logo (source) dans l'image de destination (la photo)
      imagecopy($destination, $source, $destination_x, $destination_y, 0, 0, $largeur_source, $hauteur_source);

      // On affiche l'image de destination qui a été fusionnée avec le logo
      imagejpeg($destination, "img/result.jpg");
      print_r($destination);
      imagedestroy($destination);
      unlink($target_file);
    } else {
          header("Location: editor.php?upload=KO");
    }
}
?>

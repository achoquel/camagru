<?php
session_start();
if ((isset($_SESSION) && !isset($_SESSION['id'])) || strpos($_SERVER['HTTP_REFERER'], 'editor.php') === FALSE)
  header('Location: 404.php');
if (isset($_POST) && isset($_POST['filter']) && isset($_POST['picture']) && isset($_POST['blkwt']) && strpos($_SERVER['HTTP_REFERER'], '?webcam') !== FALSE)
{
  $id = $_SESSION['id'];
  $img_data = explode( ',', $_POST['picture']);
  file_put_contents("img/tmp/capture/$id.png", base64_decode($img_data['1']));
  $src = imagecreatefrompng($_POST['filter']);
  $dst = imagecreatefrompng("img/tmp/capture/$id.png");
    header("Location: ".$_SERVER['HTTP_REFERER']."&error");
  $src_x = imagesx($src);
  $src_y = imagesy($src);
  $dst_x = imagesx($dst);
  $dst_y = imagesy($dst);
  if ($_POST['blkwt'] == 'bw')
    imagefilter($dst, IMG_FILTER_GRAYSCALE);
  imagecopy($dst, $src, '0', '0', '0', '0', $dst_x, $dst_y);
  $files = glob("img/tmp/$id-*");
  $count = '0';
  foreach ($files as $file) {
    ++$count;
  }
  imagepng($dst, "img/tmp/$id-$count.png");
  unlink("img/tmp/capture/$id.png");
  imagedestroy($src);
  imagedestroy($dst);
  header('Location: editor.php?webcam');
}
else if (isset($_POST) && isset($_POST['filter']) && isset($_POST['picture']) && isset($_POST['blkwt']) && strpos($_SERVER['HTTP_REFERER'], '?upload') !== FALSE)
{
  $id = $_SESSION['id'];
  $src = imagecreatefrompng($_POST['filter']);
  $dst = imagecreatefromjpeg($_POST['picture']);
  $src_x = imagesx($src);
  $src_y = imagesy($src);
  $dst_x = imagesx($dst);
  $dst_y = imagesy($dst);
  if ($src_x != $dst_x || $src_y != $dst_y)
  {
    $newfilter = imagecreatetruecolor($dst_x, $dst_y);
    imagealphablending($newfilter, false);
    imagesavealpha($newfilter, true);
    imagecopyresampled($newfilter, $src, 0, 0, 0, 0, $dst_x, $dst_y, $src_x, $src_y);
  }
  if ($_POST['blkwt'] == 'bw')
    imagefilter($dst, IMG_FILTER_GRAYSCALE);
  if (isset($newfilter))
    imagecopy($dst, $newfilter, '0', '0', '0', '0', $dst_x, $dst_y);
  else
    imagecopy($dst, $newfilter, '0', '0', '0', '0', $dst_x, $dst_y);
  $files = glob("img/tmp/$id-*");
  $count = '0';
  foreach ($files as $file) {
    ++$count;
  }
  imagepng($dst, "img/tmp/$id-$count.png");
  unlink($_POST['picture']);
  imagedestroy($src);
  imagedestroy($dst);
  header('Location: editor.php?upload');
}
else
  header('Location: 404.php');
?>

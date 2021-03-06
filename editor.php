<?php
header("Expires: Tue, 01 Jan 2000 00:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
session_start();
if (isset($_SESSION) && !isset($_SESSION['id']))
  header("Location: identification.php?login");
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Editor</title>
    <link rel="shortcut icon" href="img/favicon.ico">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/editor.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
  </head>
  <body style="background: url('img/bg.png'); background-repeat: repeat;">
    <div class="container">
      <div class="header">
        <a href="index.php"><div class="logo">
        <img src="img/lsg.png" alt="Logo">
      </div></a>
      <?php if (isset($_SESSION) && isset($_SESSION['id'])) {?>
      <a href="logout.php"><span class="logout"><i class="fas fa-times"></i></span></a>
      <?php } ?>
    </div>
    <div class="rainbow"></div>
    <?php if (isset($_GET) && isset($_GET['type'])){ ?>
      <div class="rainbow" style="z-index: 1; position: absolute; margin-top: 10vh; width:70vw; margin-left: 15vw; margin-right:15vw; height: 0.6vh;"></div>
      <div class="choice">
        <h1 class="choicetitle">What source would you like to use ?</h1>
        <a href="editor.php?webcam"><div class="choice1">
          <span class="webcam"><i class="fas fa-video"></i><br><h6>Webcam</h6></span>
        </div></a>
        <a href="editor.php?upload"><div class="choice2">
          <span class="upload"><i class="fas fa-upload"></i><br><h6>Upload</h6></span>
        </div></a>
      </div>
    <?php } else if (isset($_GET) && isset($_GET['webcam'])) {?>
      <div class="bodywebcam">
        <div class="filterlist" id="flist">
          <table>
            <tr>
              <td><img src="img/filters/bw.png" alt="bw" id="bw"></td>
              <td><img src="img/filters/rgb.png" alt="rgb" id="rgb"></td>
              <td><i class="fas fa-times" id="none"></i></td>
              <td><img src="img/filters/rainbow.png" alt="Rainbow" id="rainbow"></td>
              <td><img src="img/filters/bry.png" alt="Instagradient" id="insta"></td>
              <td><img src="img/filters/dog.png" alt="Dog" id="doggo"></td>
              <td><img src="img/filters/42.png" alt="42" id="fortytwo"></td>
              <td><img src="img/filters/federation.png" alt="Federation" id="federation"></td>
              <td><img src="img/filters/assembly.png" alt="Assembly" id="assembly"></td>
              <td><img src="img/filters/order.png" alt="Order" id="order"></td>
              <td><img src="img/filters/alliance.png" alt="Alliance" id="alliance"></td>
            </tr>
          </table>
        </div>
        <div class="campreview">
          <img src="img/filters/empty.png" id="photo" alt="photo">
          <img src="img/filters/empty.png" id="filter" alt="prop">
          <video id="video"></video>
          <button id="startbutton"><i class="fas fa-camera"></i></button>
          <button id="reset"><i class="fas fa-trash"></i></button>
          <form style="display: inline;" action="imageprocessing.php" method="post">
            <input type="hidden" name="picture" id="saver" value="">
            <input type="hidden" name="filter" id="filterr" value="">
            <input type="hidden" name="blkwt" id="blackwhite" value="">
            <button type="submit" id="keep"><i class="fas fa-check"></i></button>
          </form>
        </div>
        <div class="montages" id="mlist"><?php
            $id = $_SESSION['id'];
            $user_montages = glob("img/tmp/$id-*");
            foreach ($user_montages as $picture) {
              echo "<img src='$picture' alt='montage'>";
              echo "<form action='montage.php' method='post'>
                <input type='hidden' name='picture' value='$picture'>
                <input type='hidden' name='action' value='delete'>
                <button type='submit' class='delete'><i class='fas fa-trash'></i></button>
              </form>";
              echo "<form action='montage.php' method='post'>
                <input type='hidden' name='picture' value='$picture'>
                <input type='hidden' name='action' value='prepare'>
                <button type='submit' class='send'><i class='far fa-paper-plane'></i></button>
              </form>";
            }
          ?>
        </div>
        <canvas id="canvas"></canvas>
        <script src="js/editor.js" charset="utf-8"></script>
      </div>
    <?php } else if (isset($_GET) && isset($_GET['upload'])) {?>
      <div class="bodywebcam">
        <div class="filterlist" id="flist">
          <table>
            <tr>
              <td><img src="img/filters/bw.png" alt="bw" id="bw"></td>
              <td><img src="img/filters/rgb.png" alt="rgb" id="rgb"></td>
              <td><i class="fas fa-times" id="none"></i></td>
              <td><img src="img/filters/rainbow.png" alt="Rainbow" id="rainbow"></td>
              <td><img src="img/filters/bry.png" alt="Instagradient" id="insta"></td>
              <td><img src="img/filters/dog.png" alt="Dog" id="doggo"></td>
              <td><img src="img/filters/42.png" alt="42" id="fortytwo"></td>
              <td><img src="img/filters/federation.png" alt="Federation" id="federation"></td>
              <td><img src="img/filters/assembly.png" alt="Assembly" id="assembly"></td>
              <td><img src="img/filters/order.png" alt="Order" id="order"></td>
              <td><img src="img/filters/alliance.png" alt="Alliance" id="alliance"></td>
            </tr>
          </table>
        </div>
        <div class="campreview">
          <img src="img/filters/empty.png" id="filter" alt="prop">
          <img src="<?php if (isset($_SESSION['currentmontage'])){ echo $_SESSION['currentmontage']; unset($_SESSION['currentmontage']);} else echo "img/nia.jpg";?>" id="photo2" alt="photo">
          <form action="imageupload.php" method="post" id="form" enctype="multipart/form-data">
            <h2 class='select'>Select image to upload:</h2>
            <input type="file" name="fileToUpload" id="fileToUpload">
            <?php if (isset($_GET) && isset($_GET['error'])){ ?>
              <h5 class='error'>An error occurred during upload. Be sure to respect the requirements listed below !</h5>
            <?php } ?>
            <h3 class='recommendations'>Max file size: 2Mo. Recommended format: 4/3.<br>
            If the picture is not a 4/3 picture, the filter applied to it might be deformed.
            If the picture contains a transparent background, the filter will be applied as if there was no transparency.<br>Allowed files: .jpg, .jpeg</h3>
            <button id="startbutton"><i class="fas fa-upload"></i></button>
          </form>
          <button id="reset"><i class="fas fa-trash"></i></button>
          <form style="display: inline;" action="imageprocessing.php" method="post">
            <input type="hidden" name="picture" id="saver" value="">
            <input type="hidden" name="filter" id="filterr" value="">
            <input type="hidden" name="blkwt" id="blackwhite" value="">
            <button type="submit" id="keep"><i class="fas fa-check"></i></button>
          </form>
        </div>
        <div class="montages" id="mlist"><?php
            $id = $_SESSION['id'];
            $user_montages = glob("img/tmp/$id-*");
            foreach ($user_montages as $picture) {
              echo "<img src='$picture' alt='montage'>";
              echo "<form action='montage.php' method='post'>
                <input type='hidden' name='picture' value='$picture'>
                <input type='hidden' name='action' value='delete'>
                <button type='submit' class='delete'><i class='fas fa-trash'></i></button>
              </form>";
              echo "<form action='montage.php' method='post'>
                <input type='hidden' name='picture' value='$picture'>
                <input type='hidden' name='action' value='prepare'>
                <button type='submit' class='send'><i class='far fa-paper-plane'></i></button>
              </form>";
            }
          ?>
        </div>
        <script src="js/upload.js" charset="utf-8"></script>
      </div>
    <?php } else header("Location: 404.php") ?>
    <div class="footer">
      <a href="index.php"><span class="bbutton1"><i class="fas fa-home"></i></span></a>
      <a href="search.php"><span class="bbutton2"><i class="fas fa-search"></i><br></span></a>
      <a href="editor.php?type"><span class="bbutton3"><i class="fas fa-plus"></i><br></span></a>
      <a href="notifications.php"><span class="bbutton4"><i class="far fa-bell"></i></span></a>
      <a href="profile.php"><span class="bbutton5"><i class="fas fa-user"></i></span></a>
    </div>
  </div>
  </body>
</html>

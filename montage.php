<?php
session_start();
if ((isset($_SESSION) && !isset($_SESSION['id'])) || strpos($_SERVER['HTTP_REFERER'], 'editor.php') === FALSE)
  header('Location: 404.php');
if (isset($_POST) && isset($_POST['action']) && isset($_POST['picture']))
{
  if ($_POST['action'] == 'delete')
  {
    if (file_exists($_POST['picture']))
      unlink($_POST['picture']);
    header('Refresh: 0; URL='.$_SERVER['HTTP_REFERER']);
  }
  else if ($_POST['action'] == 'prepare' && file_exists($_POST['picture']))
  {?>
  <html lang="en" dir="ltr">
    <head>
      <meta charset="utf-8">
      <title>Post your picture !</title>
      <link rel="shortcut icon" href="img/favicon.ico">
      <link rel="stylesheet" href="css/main.css">
      <link rel="stylesheet" href="css/send.css">
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
      <div class="body">
        <form class="" action="montage.php" method="post">
          <img src="<?php echo $_POST['picture']; ?>" alt="">
          <input type="text" name="description" value="" placeholder="📝 Description" maxlength="256" required>
          <input type="hidden" name="picture" value="<?php echo $_POST['picture']; ?>">
          <input type="hidden" name="action" value="post">
          <input type="submit" name="submit" value="Post on Camagru">
        </form>
      </div>
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
<?php }
  else if ($_POST['action'] == 'post')
  {
    if (file_exists($_POST['picture']))
    {
      $id = $_SESSION['id'];
      $files = glob("img/posts/$id-*");
      $count = '0';
      foreach ($files as $file) {
        ++$count;
      }
      rename($_POST['picture'], "img/posts/$id-$count.png");
      $img = "img/posts/$id-$count.png";
      require('config/database.php');
      try {
          $dbh = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
          $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
          $request = $dbh->prepare('INSERT INTO `posts`(`user_id`, `picture`, `description`) VALUES (:uid, :pic, :dsc)');
          $request->bindParam(':uid', $id);
          $request->bindParam(':pic', $img);
          $request->bindParam(':dsc', $_POST['description']);
          $request->execute();
          $dbh = null;
          $request = null;
          } catch (PDOException $e) {
          print "Erreur !: " . $e->getMessage() . "<br/>";
          die();
      }
      header('Location: index.php');
    }
  }
  else
    header('Location: 404.php');
}
else
  header('Location: 404.php');


?>

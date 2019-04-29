<?php
  session_start();
  if (isset($_SESSION) && !isset($_SESSION['id']))
    header("Location: identification.php?login");
  if (isset($_GET) && isset($_GET['user']))
  {
      require('config/database.php');
      try {
        $dbh = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        //Check if user exists
        $request = $dbh->prepare('SELECT `username` FROM `users` WHERE `username` = :user');
        $request->bindParam(':user', $_GET['user']);
        $request->execute();
        $exists = $request->fetch(PDO::FETCH_ASSOC);
        if ($exists)
        {
          $request = $dbh->prepare($request = 'SELECT `username`, `firstname`, `lastname`, `country`, `city`, `job`, `avatar` FROM `users` WHERE `username` = :user');
          $request->bindParam(':user', $_GET['user']);
          $request->execute();
          $infos = $request->fetch(PDO::FETCH_ASSOC);
          $avatar = $infos['avatar'];
          $username = $infos['username'];
          if (!empty($infos['firstname']) && !empty($infos['lastname']))
            $realname = $infos['firstname']." ".$infos['lastname'];
          else if (empty($infos['firstname']) && !empty($infos['lastname']))
            $realname = $infos['lastname'];
          else if (!empty($infos['firstname']) && empty($infos['lastname']))
            $realname = $infos['firstname'];
          else if (empty($infos['firstname']) && empty($infos['lastname']))
            $realname = '';
          if (!empty($infos['city']) && !empty($infos['country']))
            $location = $infos['city'].", ".$infos['country'];
          else if (empty($infos['city']) && !empty($infos['country']))
            $location = $infos['country'];
          else if (!empty($infos['city']) && empty($infos['country']))
            $location = $infos['city'];
          else if (empty($infos['city']) && empty($infos['country']))
            $location = '';
          $job = $infos['job'];
          $request = $dbh->prepare('SELECT `picture`, `post_id` FROM `posts` INNER JOIN `users` ON `users`.`user_id` = `posts`.`user_id` WHERE `users`.`username` = :uname');
          $request->bindParam(':uname', $_GET['user']);
          $request->execute();
          $pics = $request->fetchAll(PDO::FETCH_ASSOC);
        }
        else
          header('Location: 404.php');
      } catch (PDOException $e) {
        print "Erreur !: " . $e->getMessage() . "<br/>";
        die();
      }
    }
  else
  {
    require('config/database.php');
    try {
      $dbh = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
      $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $request = $dbh->prepare('SELECT `username`, `firstname`, `lastname`, `country`, `city`, `job`, `avatar` FROM `users` WHERE `user_id` = :uid');
      $request->bindParam(':uid', $_SESSION['id']);
      $request->execute();
      $infos = $request->fetch(PDO::FETCH_ASSOC);
      $request = $dbh->prepare('SELECT `picture`, `post_id` FROM `posts` INNER JOIN `users` ON `users`.`user_id` = `posts`.`user_id` WHERE `users`.`user_id` = :uid');
      $request->bindParam(':uid', $_SESSION['id']);
      $request->execute();
      $pics = $request->fetchAll(PDO::FETCH_ASSOC);
      $request = null;
      $dbh = null;
    } catch (PDOException $e) {
      print "Erreur !: " . $e->getMessage() . "<br/>";
      die();
    }
    $avatar = $infos['avatar'];
    $username = $infos['username'];
    if (!empty($infos['firstname']) && !empty($infos['lastname']))
      $realname = $infos['firstname']." ".$infos['lastname'];
    else if (empty($infos['firstname']) && !empty($infos['lastname']))
      $realname = $infos['lastname'];
    else if (!empty($infos['firstname']) && empty($infos['lastname']))
      $realname = $infos['firstname'];
    else if (empty($infos['firstname']) && empty($infos['lastname']))
      $realname = '';
    if (!empty($infos['city']) && !empty($infos['country']))
      $location = $infos['city'].", ".$infos['country'];
    else if (empty($infos['city']) && !empty($infos['country']))
      $location = $infos['country'];
    else if (!empty($infos['city']) && empty($infos['country']))
      $location = $infos['city'];
    else if (empty($infos['city']) && empty($infos['country']))
      $location = '';
    $job = $infos['job'];
  }
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title><?php echo $infos['username'];?>'s Profile</title>
    <link rel="shortcut icon" href="img/favicon.ico">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/profile.css">
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
      <div class="rainbow">
      </div>
      <div class="body">
        <img class='profilepicture' src="data:image/png;base64,<?php echo $avatar; ?>" alt="avatar">
        <h1 class="username"><?php echo $username; ?></h1>
        <h2 class="realname"><?php echo $realname; ?></h2>
        <?php if (!empty($location))
        {?>
        <h2 class="location"><i class="fas fa-map-marker-alt"></i> <?php echo $location; ?></h2>
        <?php
        } if (!empty($job))
        {?>
        <h2 class="job"><i class="fas fa-suitcase"></i> <?php echo $job; ?></h2>
        <?php
        }
        if (isset($_SESSION) && isset($_SESSION['id']) && isset($_GET) && !isset($_GET['user'])) {?>
          <form action="accountmanager.php" method="post">
            <input type="submit" name="AccountManager" value="Manage my Account">
          </form>
        <?php } ?>
        <hr>
        <?php
          foreach ($pics as $picture) {
            $photo = $picture['picture'];
            $post_id = $picture['post_id'];
            echo "<div class='pic'>";
            echo "<a href='detail.php?id=$post_id'>";
            echo "<div class='picture'><img src='$photo' alt='photo'></div>";
            echo "</a>";
            echo "</div>";
          }
        ?>
      </div>
      <div class="footer">
        <a href="index.php"><span class="bbutton1"><i class="fas fa-home"></i></span></a>
        <a href="search.php"><span class="bbutton2"><i class="fas fa-search"></i></span></a>
        <a href="editor.php?type"><span class="bbutton3"><i class="fas fa-plus"></i></span></a>
        <a href="notifications.php"><span class="bbutton4"><i class="far fa-bell"></i></span></a>
        <a href="profile.php"><span class="bbutton5"><i class="fas fa-user"></i></span></a>
      </div>
    </div>
  </body>
</html>

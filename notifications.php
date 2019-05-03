<?php
session_start();
if (isset($_SESSION) && !isset($_SESSION['id']))
  header('Location: identification.php?login');
require('config/database.php');
try {
  $dbh = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
  $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  if (isset($_GET['clear']))
  {
      $request = $dbh->prepare('DELETE FROM `notif` WHERE `to_user_id` = :uid');
      $request->bindParam(':uid', $_SESSION['id']);
      $request->execute();
      $request = null;
      $dbh = null;
      header('Location: notifications.php');
  } else {
  $request = 'SELECT `fu`.`username` AS `from`, `type`, `tu`.`username` AS `to`, `av`.`avatar` AS `avatar`
  FROM `notif`
  INNER JOIN `users` fu ON `notif`.`from_user_id` = `fu`.`user_id`
  INNER JOIN `users` av ON `notif`.`from_user_id` = `av`.`user_id`
  INNER JOIN `users` tu ON `notif`.`to_user_id` = `tu`.`user_id`
  WHERE `to_user_id` = "'.$_SESSION['id'].'"';
  $notifs = array();
  foreach ($dbh->query($request) as $result)
  {
    if (isset($result['from']) && isset($result['type']))
      $notifs[] = array($result['from'], $result['type'], $result['avatar']);
  }
}
  $request = null;
  $dbh = null;
} catch (PDOException $e) {
print "Erreur !: " . $e->getMessage() . "<br/>";
die();
}
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Camagru</title>
    <link rel="shortcut icon" href="img/favicon.ico">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/notif.css">
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
        <h1><i class="fas fa-bell"></i> Notifications</h1>
        <?php if (!empty($notifs))
        { ?>
        <form action="notifications.php?clear" method="post">
          <button type="submit" class="delbt">
            <i class="fas fa-trash-alt"></i>
          </button>
        </form>
      <?php } ?>
        <hr>
        <?php
          if (empty($notifs))
            echo "<h1 class='nothing'><i class='far fa-times-circle'></i><br>You don't have any new notification !</h2>";
          else {
            foreach ($notifs as $notif)
            {
              if (isset($notif) && isset($notif['0']) && isset($notif['1']))
              {
                $avatar = htmlspecialchars($notif['2']);
                $username = htmlspecialchars($notif['0']);
                if ($notif['1'] == '2')
                  echo "<div class='not'><img class='pp' src='$avatar' alt='$username avatar'> <h2 class='from'>".$username."</h2><h2 class='notif'>     liked your picture !"."</h2></div>";
                else
                  echo "<div class='not'><img class='pp' src='$avatar' alt='$username avatar'> <h2 class='from'>".$username."</h2><h2 class='notif'>     commented your picture !"."</h2></div>";
                echo "<hr>";
              }
            }
          }?>
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

<?php
session_start();
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Search</title>
    <link rel="shortcut icon" href="img/favicon.ico">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/search.css">
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
        <form class="search" action="getsearch.php" method="post">
          <input type="text" name="object" minlength="1" placeholder="Search Camagru's images or users..." value="" required/>
          <button type="submit" class="srcbt">
            <i class="fas fa-search"></i>
          </button>
        </form>
        <?php
        if (isset($_SESSION) && isset($_SESSION['search_users']) && isset($_SESSION['search_pics']))
        {
          if (empty($_SESSION['search_users']) && empty($_SESSION['search_pics']))
          {
            echo "<h1 class='nothing'><i class='far fa-times-circle'></i><br>Nothing found for your search !</h2>";
          } else {
          echo "<h1 class='result'><i class='fas fa-search'></i> Results :<h1><br>";
          echo "<h1 class='title'>Users</h1><hr>";
          foreach ($_SESSION['search_users'] as $users)
          {
            $user = $users['username'];
            $avatar = $users['avatar'];
            echo "<div class='res'><a class='user' href='profile.php?user=$user'><img class='pp' src='$avatar' alt='$user avatar'><h1>$user</h1></a></div>";
          }
          echo "<br><br><h1 class='title'>Pictures</h1><hr>";
          foreach ($_SESSION['search_pics'] as $picture)
          {
              $username = htmlspecialchars($picture['username']);
              $imgsrc = htmlspecialchars($picture['picture']);
              $img_id = htmlspecialchars($picture['post_id']);
              $desc  = htmlspecialchars($picture['description']);
              echo "<a href='detail.php?id=$img_id'><div class='pic'>";
              echo "<div class='user'><h1 class='username'>$username</h1></div>";
              if ($picture['private'] == 0 || ($picture['private'] == 1 && isset($_SESSION['id'])))
                echo "<div class='picture'><img src='$imgsrc' alt='Picture of $username'></div>";
              else
                echo "<div class='picture'><img src='img/private.png' alt='Picture of $username'></div>";
              if ($picture['private'] == 0 || ($picture['private'] == 1 && isset($_SESSION['id'])))
                echo "<div class='desc'><h1 class='descuser'>$username</h1>   <h2 class='desccont'>$desc</h2></div>";
              echo "</div></a>";
            }
          unset($_SESSION['search_users']);
          unset($_SESSION['search_pics']);
        }
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

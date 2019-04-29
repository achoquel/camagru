<?php
  session_start();
  if (!isset($_GET['page']))
    $_GET['page'] = '0';
  try {
    require('config/database.php');
    $dbh = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $request = 'SELECT COUNT(`post_id`) AS `total` FROM `posts`';
    $request = $dbh->prepare($request);
    $request->execute();
    $result = $request->fetch(PDO::FETCH_ASSOC);
    $total = $result['total'];
  } catch (PDOException $e) {
  print "Erreur !: " . $e->getMessage() . "<br/>";
  die();
  }
  if ($total - $_GET['page'] * 5 < 0 || $_GET['page'] < 0)
    header('Location: index.php');
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Camagru</title>
    <link rel="shortcut icon" href="img/favicon.ico">
    <link rel="stylesheet" href="css/main.css">
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
        <?php
          try {
            require('config/database.php');
            $dbh = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $request = 'SELECT `post_id`, `posts`.`user_id`, `us`.`username` AS `username`, `pu`.`pref_private` AS `private`, `picture`, `description`, `av`.`avatar` AS `avatar`
            FROM `posts`
            INNER JOIN `users` us ON `posts`.`user_id` = `us`.`user_id`
            INNER JOIN `users` pu ON `posts`.`user_id` = `pu`.`user_id`
            INNER JOIN `users` av ON `posts`.`user_id` = `av`.`user_id`
            WHERE `post_id` > (:total - :page * 5 - 5) AND `post_id` <= (:total - :page * 5)
            ORDER BY `post_id` DESC';
            $request = $dbh->prepare($request);
            $page = $_GET['page'];
            settype ($page , 'integer');
            settype ($total , 'integer');
            $request->bindParam(':page', $page);
            $request->bindParam(':total', $total);
            $request->execute();
            $result = $request->fetchAll(PDO::FETCH_ASSOC);
            // POST_ID(PAGINATION + like comments) | USERNAME->USER_ID(POSTS) | IMG_SRC | DESCRIPTION | LIKED->POST_ID->USER_ID(LIKES)
          } catch (PDOException $e) {
          print "Erreur !: " . $e->getMessage() . "<br/>";
          die();
          }
          if (isset($result) && !empty($result))
          {
            foreach ($result as $picture)
            {
                $userid = $picture['user_id'];
                $username = htmlspecialchars($picture['username']);
                $imgsrc = $picture['picture'];
                $img_id = $picture['post_id'];
                $desc  = htmlspecialchars($picture['description']);
                $avatar = $picture['avatar'];
                echo "<div class='pic'>";
                echo "<div class='user'><img class='pp' src='data:image/png;base64,$avatar' alt='$username avatar'> <h1 class='username'>$username</h1></div>";
                if ($picture['private'] == 0 || ($picture['private'] == 1 && isset($_SESSION['id'])))
                  echo "<div class='picture'><img src='$imgsrc' alt='Picture of $username'></div>";
                else
                  echo "<div class='picture'><img src='img/private.png' alt='Picture of $username'></div>";
                if (isset($_SESSION) && isset($_SESSION['id']))
                {
                  $request = $dbh->prepare('SELECT * FROM `likes` WHERE `post_id` = :post AND `user_id` = :uid');
                  $request->bindParam(':post', $img_id);
                  $request->bindParam(':uid', $_SESSION['id']);
                  $request->execute();
                  $isLiked = $request->fetch(PDO::FETCH_ASSOC);
                  if (empty($isLiked))
                    $liked = 0;
                  else
                    $liked = 1;
                  $page = $_GET['page'];
                  echo "<a href='like.php?id=$img_id&p=$page' class='like'";
                  if ($liked == 1)
                    echo " style='color:red;'><i class='fas fa-heart'></i></a>";
                  else
                    echo "><i class='far fa-heart'></i></a>";
                  echo "<a href='detail.php?id=$img_id' class='comment'><i class='far fa-comment-dots'></i></a>";
                }
                if ($picture['private'] == 0 || ($picture['private'] == 1 && isset($_SESSION['id'])))
                  echo "<div class='desc'><h1 class='descuser'>$username</h1>   <h2 class='desccont'>$desc</h2></div>";
                echo "</div>";
            }
            ?>
            <div class="pagination">
            <form class="minus" action="index.php" method="get">
              <input type="hidden" name="page" value="<?php $pagem = $_GET['page'] - 1; echo $pagem;?>">
              <input type="submit" value="❮" <?php if ($pagem < 0) echo "disabled"; ?>/>
            </form>
            <form class="more" action="index.php" method="get">
              <input type="hidden" name="page" value="<?php $pagep = $_GET['page'] + 1; echo $pagep;?>">
              <input type="submit" value="❯" <?php if ($total - $_GET['page'] * 5 < 5) echo "disabled";?>/>
            </form>
            </div>
            <?php
          }
          else
          {
            echo "<h1 class='nothing'><i class='fas fa-images'></i><br>There is no image on Camagru yet ! Why wouldn't you be the first to post ?</h2>";
          }
          $request = null;
          $dbh = null;
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

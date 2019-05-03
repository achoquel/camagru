<?php
session_start();
if (isset($_SESSION) && !isset($_SESSION['id']))
  header('Location: identification.php?login');
if (isset($_GET) && isset($_GET['id']))
{
  require('config/database.php');
  try {
    $dbh = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $request = $dbh->prepare('SELECT `post_id` FROM `posts` WHERE `post_id` = :pid');
    $request->bindParam(':pid', $_GET['id']);
    $request->execute();
    $exists = $request->fetch(PDO::FETCH_ASSOC);
    if (empty($exists))
      header('Location: 404.php');
    else
    {
        /*POSTS :
          - POST_ID
          - USER_ID -> USERNAME
          - PICTURE
          - DESCRIPTION

          USERS:
            - USERNAME (INNER JOIN)

          COMMENTS:
            - USER_ID -> USERNAME
            - POST_ID
            - COMMENT

        1ST REQUEST: POST INFOS

        SELECT `users`.`username` AS `poster`, `picture`, `description`
        FROM `posts`
        INNER JOIN `users` ON `posts`.`user_id` = `users`.`user_id`
        WHERE `post_id` = :pid

        2ND REQUEST: COMMENT LIST

        SELECT `users`.`username` AS `commenter`, `comment`
        FROM `comments`
        INNER JOIN `users` ON `users`.`user_id` = `comments`.`user_id`
        WHERE `post_id` = :pid
      */
      $request = $dbh->prepare('SELECT `users`.`username` AS `poster`, `picture`, `description`
      FROM `posts`
      INNER JOIN `users` ON `posts`.`user_id` = `users`.`user_id`
      WHERE `post_id` = :pid');
      $request->bindParam(':pid', $_GET['id']);
      $request->execute();
      $picture = $request->fetch(PDO::FETCH_ASSOC);
      $request = $dbh->prepare('SELECT `comments`.`user_id`, `comments`.`comment_id`, `users`.`username` AS `commenter`, `comment`
      FROM `comments`
      INNER JOIN `users` ON `users`.`user_id` = `comments`.`user_id`
      WHERE `post_id` = :pid
      ORDER BY `comment_id`');
      $request->bindParam(':pid', $_GET['id']);
      $request->execute();
      $comments = $request->fetchAll(PDO::FETCH_ASSOC);
      $request = $dbh->prepare('SELECT * FROM `likes` WHERE `post_id` = :post AND `user_id` = :uid');
      $request->bindParam(':post', $_GET['id']);
      $request->bindParam(':uid', $_SESSION['id']);
      $request->execute();
      $isLiked = $request->fetch(PDO::FETCH_ASSOC);
    }
    $request = null;
    $dbh = null;
  } catch (PDOException $e) {
  print "Erreur !: " . $e->getMessage() . "<br/>";
  die();
  }
}
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Camagru</title>
    <link rel="shortcut icon" href="img/favicon.ico">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/detail.css">
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
          $username = htmlspecialchars($picture['poster']);
          $imgsrc = htmlspecialchars($picture['picture']);
          $desc  = htmlspecialchars($picture['description']);
          echo "<div class='pic'>";
          echo "<div class='user'><h1 class='username'>$username's post</h1></div>";
          echo "<div class='picture'><img src='$imgsrc' alt='Picture of $username'></div>";
          echo "<div class='desc'><h1 class='descuser'>$username</h1>   <h2 class='desccont'>$desc</h2></div>";
          echo "<hr class='sep'>";
          foreach($comments as $comment)
          {
            $user = htmlspecialchars($comment['commenter']);
            $text = htmlspecialchars($comment['comment']);
            echo "<div class='desc'>";
            if ($comment['user_id'] == $_SESSION['id'])
            {
              $cid = htmlspecialchars($comment['comment_id']);
              $id = htmlspecialchars($_GET['id']);
            ?><form action='comment.php?del' style="display: inline;" method='post'>
                    <input type='hidden' name='id' value="<?php echo $id; ?>">
                    <input type='hidden' name='cid' value="<?php echo $cid; ?>">
                <button type='submit' class='delbtn'>
                  <i class='far fa-trash-alt'></i>
                </button>
              </form><?php
            }
            echo "<h1 class='descuser'>$user</h1>   <h2 class='desccont'>$text</h2></div>";
          }
          if (empty($isLiked))
            $liked = 0;
          else
            $liked = 1;
          echo "<a href='like.php?id=".$_GET['id']."&detail' class='like'";
          if ($liked == 1)
            echo " style='color:red;'><i class='fas fa-heart'></i></a>";
          else
            echo "><i class='far fa-heart'></i></a>";
          ?>
          <form action="comment.php?add" method="post" style="display: inline;">
            <input type="text" name="comment" minlength="1" maxlength="256" placeholder="Comment this picture..." value="" required/>
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($_GET['id']); ?>">
            <button type="submit" class="srcbt">
              <i class="fas fa-arrow-right"></i>
            </button>
          </form>
          <?php
          echo "</div>";
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

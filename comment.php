<?php
session_start();
if (isset($_SESSION) && !isset($_SESSION['id']))
  header('Location: 404.php');
if (isset($_POST) && isset($_POST['comment']) && isset($_POST['id']) && isset($_GET) && isset($_GET['add']))
{
  $post_id = $_POST['id'];
  if (strlen($_POST['comment']) <= 0 && strlen($_POST['comment']) > 256)
    header("Location: detail.php?id=$post_id&error=len");
  else
  {
    require('config/database.php');
    try {
      $dbh = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
      $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $request = $dbh->prepare('INSERT INTO `comments` (`user_id`, `post_id`, `comment`) VALUES (:uid, :pid, :comment)');
      $request->bindParam(':uid', $_SESSION['id']);
      $request->bindParam(':pid', $_POST['id']);
      $request->bindParam(':comment', $_POST['comment']);
      $request->execute();
      $request = $dbh->prepare('SELECT `username` FROM `users` WHERE `user_id` = :uid');
      $request->bindParam(':uid', $_SESSION['id']);
      $request->execute();
      $res = $request->fetch(PDO::FETCH_ASSOC);
      $from = $res['username'];
      $request = $dbh->prepare('SELECT `username`, `email`
                                FROM `users`
                                INNER JOIN `posts` ON `users`.`user_id` = `posts`.`user_id`
                                WHERE `post_id` = :pid');
      $request->bindParam(':pid', $_POST['id']);
      $request->execute();
      $res = $request->fetch(PDO::FETCH_ASSOC);
      $email = $res['email'];
      $to = $res['username'];
      $request = null;
      $dbh = null;
      require('sendmail.php');
      notif($email, 'commented', $to, $from);
      header("Location: detail.php?id=$post_id");
    } catch (PDOException $e) {
    print "Erreur !: " . $e->getMessage() . "<br/>";
    die();
    }
  }
}
else if (isset($_POST) && isset($_POST['id']) && isset($_POST['cid']) && isset($_GET) && isset($_GET['del']))
{
  $post_id = $_POST['id'];
  require('config/database.php');
  try {
    $dbh = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $request = $dbh->prepare('SELECT `comment_id` FROM `comments` WHERE `comment_id` = :cid AND `user_id` = :uid AND `post_id` = :pid');
    $request->bindParam(':uid', $_SESSION['id']);
    $request->bindParam(':pid', $_POST['id']);
    $request->bindParam(':cid', $_POST['cid']);
    $request->execute();
    $exists = $request->fetch(PDO::FETCH_ASSOC);
    if (!empty($exists))
    {
      $request = $dbh->prepare('DELETE FROM `comments` WHERE `comment_id` = :cid');
      $request->bindParam(':cid', $_POST['cid']);
      $request->execute();
      header("Location: detail.php?id=$post_id");
    }
    else
      header('Location: 404.php');
    $request = null;
    $dbh = null;
    print_r($exists);
  } catch (PDOException $e) {
  print "Erreur !: " . $e->getMessage() . "<br/>";
  die();
  }
}
else
  header("Location: 404.php");
?>

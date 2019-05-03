<?php
session_start();
if (isset($_SESSION) && !isset($_SESSION['id']))
  header('Location: identification.php?login');
if (isset($_GET) && isset($_GET['id']))
{
  $pagefrom = $_SERVER['HTTP_REFERER'];
  require('config/database.php');
  try {
    $dbh = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $request = $dbh->prepare('SELECT `post_id`, `user_id` FROM `posts` WHERE `post_id` = :pid');
    $request->bindParam(':pid', $_GET['id']);
    $request->execute();
    $exists = $request->fetch(PDO::FETCH_ASSOC);
    if (!empty($exists))
    {
      $request = $dbh->prepare('SELECT * FROM `likes` WHERE `post_id` = :pid AND `user_id` = :uid');
      $request->bindParam(':pid', $_GET['id']);
      $request->bindParam(':uid', $_SESSION['id']);
      $request->execute();
      $liked = $request->fetch(PDO::FETCH_ASSOC);
      if (empty($liked))
      {
        $request = $dbh->prepare('INSERT INTO `likes`(`user_id`, `post_id`) VALUES (:uid, :pid)');
        $request->bindParam(':pid', $_GET['id']);
        $request->bindParam(':uid', $_SESSION['id']);
        $request->execute();
        $request = $dbh->prepare('INSERT INTO `notif`(`from_user_id`, `type`, `to_user_id`) VALUES (:uid, "2", :tuid)');
        $request->bindParam(':uid', $_SESSION['id']);
        $request->bindParam(':tuid', $exists['user_id']);
        $request->execute();
        $request = $dbh->prepare('SELECT `username`,`email`,`pref_mail` FROM `users` WHERE `user_id` = :tuid OR `user_id` = :uid');
        $request->bindParam(':uid', $_SESSION['id']);
        $request->bindParam(':tuid', $exists['user_id']);
        $request->execute();
        $res = $request->fetchAll(PDO::FETCH_ASSOC);
        $from = $res['0']['username'];
        if (isset($res['1']))
        {
          $email = $res['1']['email'];
          $to = $res['1']['username'];
          $pref = $res['1']['pref_mail'];
        }
        else
        {
          $email = $res['0']['email'];
          $to = $res['1']['username'];
          $pref = $res['0']['pref_mail'];
        }
        $request = null;
        $dbh = null;
        if ($pref == '1')
        {
          require('sendmail.php');
          notif($email, 'liked', $to, $from);
        }
          header("Location: $pagefrom");
      }
      else
      {
        $request = $dbh->prepare('DELETE FROM `likes` WHERE `user_id` = :uid AND `post_id` = :pid');
        $request->bindParam(':pid', $_GET['id']);
        $request->bindParam(':uid', $_SESSION['id']);
        $request->execute();
        $request = null;
        $dbh = null;
        header("Location: $pagefrom");
      }
    }
    else
      header('Location: 404.php');
  } catch (PDOException $e) {
  print "Erreur !: " . $e->getMessage() . "<br/>";
  die();
  }
  $request = null;
  $dbh = null;
}
  else
    header('Location: 404.php');

?>

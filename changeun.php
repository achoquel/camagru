<?php
  session_start();
  if (isset($_SESSION) && !isset($_SESSION['id']))
    header("Location: identification.php?login");
  else
  {
      if (isset($_POST) && isset($_POST['newusername']) && isset($_POST['passwd']) && isset($_POST['submit']) && $_POST['submit'] == 'Modify Username')
      {
        if (strlen($_POST['newusername']) == 0 || strlen($_POST['newusername']) > 32)
        {
          $err = 1;
          header("Location: accountmanager.php?error=uc");
        }
        if (preg_match('/^([a-z]|[A-Z]|[0-9])+$/', $_POST['newusername']) === 0)
        {
          $err = 1;
          header("Location: accountmanager.php?error=uc");
        }
        if (!isset($err))
        {
          $new_username = strtolower($_POST['newusername']);
          require('config/database.php');
          $dbh = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
          $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
          $request = 'SELECT `username` FROM `users`';
          foreach ($dbh->query($request) as $result)
          {
            if (isset($result['username']) && $result['username'] == $new_username)
            {
              $error = 1;
              header('Location: accountmanager.php?error=uu');
            }
          }
          $pw = hash('whirlpool', $_POST['passwd']);
          $request = 'SELECT `password` FROM `users` WHERE `user_id` = "'.$_SESSION['id'].'"';
          foreach ($dbh->query($request) as $result)
          {
            if (isset($result['password']) && $result['password'] != $pw)
            {
              $error = 1;
              header('Location: accountmanager.php?error=up');
            }
          }
          if (!isset($error))
          {
            $request = $dbh->prepare('UPDATE `users` SET `username` = :new_username WHERE `user_id` = :user_id');
            $request->bindParam(':new_username', $new_username);
            $request->bindParam(':user_id', $_SESSION['id']);
            $request->execute();
            $request = null;
            $dbh = null;
            header("Location: accountmanager.php?success=username");
          }
          $request = null;
          $dbh = null;
        }
      }
      else
        header("Location: accountmanager.php?error");
  }
?>

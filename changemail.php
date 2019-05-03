<?php
  session_start();
  if (isset($_SESSION) && !isset($_SESSION['id']))
    header("Location: identification.php?login");
  else
  {
      if (isset($_POST) && isset($_POST['nemail']) && isset($_POST['passwd']) && isset($_POST['submit']) && $_POST['submit'] == 'Modify Email')
      {
        if (empty($_POST['nemail']) || filter_var($_POST['nemail'], FILTER_VALIDATE_EMAIL) === false)
        {
          $error = 1;
          header("Location: accountmanager.php?error=ev");
        }
        else
        {
          $new_email = $_POST['nemail'];
          require('config/database.php');
          $dbh = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
          $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
          $request = 'SELECT `email` FROM `users`';
          foreach ($dbh->query($request) as $result)
          {
            if (isset($result['email']) && $result['email'] == $new_email)
            {
              $error = 1;
              header('Location: accountmanager.php?error=eu');
            }
          }
          $pw = hash('whirlpool', $_POST['passwd']);
          $request = 'SELECT `password` FROM `users` WHERE `user_id` = "'.$_SESSION['id'].'"';
          foreach ($dbh->query($request) as $result)
          {
            if (isset($result['password']) && $result['password'] != $pw)
            {
              $error = 1;
              header('Location: accountmanager.php?error=ep');
            }
          }
          if (!isset($error))
          {
            $request = $dbh->prepare('UPDATE `users` SET `email` = :new_email WHERE `user_id` = :user_id');
            $request->bindParam(':new_email', $new_email);
            $request->bindParam(':user_id', $_SESSION['id']);
            $request->execute();
            $request = null;
            $dbh = null;
            header("Location: accountmanager.php?success=email");
          }
          $request = null;
          $dbh = null;
        }
      }
      else
        header("Location: accountmanager.php?error");
  }
?>

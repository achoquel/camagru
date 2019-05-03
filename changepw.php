<?php
  session_start();
  if (isset($_SESSION) && !isset($_SESSION['id']))
    header("Location: identification.php?login");
  else
  {
      if (isset($_POST) && isset($_POST['oldpasswd']) && isset($_POST['newpasswd']) && isset($_POST['newverifpasswd']) && isset($_POST['submit']) && $_POST['submit'] == 'Modify Password')
      {
        if (strlen($_POST['newpasswd']) < 6)
        {
          $err = 1;
          header('Location: accountmanager.php?error=pc');
        }
        if ($_POST['newpasswd'] != $_POST['newverifpasswd'])
        {
          $err = 1;
          header('Location: accountmanager.php?error=pm');
        }
        $uppercase = preg_match('/[A-Z]/', $_POST['newpasswd']);
        $lowercase = preg_match('/[a-z]/', $_POST['newpasswd']);
        $number    = preg_match('/[0-9]/', $_POST['newpasswd']);
        $specialChars = preg_match('/[^\w]/', $_POST['newpasswd']);
        if(!$uppercase || !$lowercase || !$number || !$specialChars)
        {
          $err = 1;
          header('Location: accountmanager.php?error=pc');
        }
        if (!isset($err))
        {
          $new_password = hash('whirlpool', $_POST['newpasswd']);
          require('config/database.php');
          $dbh = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
          $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
          $pw = hash('whirlpool', $_POST['oldpasswd']);
          $request = 'SELECT `password` FROM `users` WHERE `user_id` = "'.$_SESSION['id'].'"';
          foreach ($dbh->query($request) as $result)
          {
            if (isset($result['password']) && $result['password'] != $pw)
            {
              $error = 1;
              header('Location: accountmanager.php?error=pi');
            }
          }
          if (!isset($error))
          {
            $request = $dbh->prepare('UPDATE `users` SET `password` = :new_password WHERE `user_id` = :user_id');
            $request->bindParam(':new_password', $new_password);
            $request->bindParam(':user_id', $_SESSION['id']);
            $request->execute();
            $request = null;
            $dbh = null;
            header("Location: accountmanager.php?success=password");
          }
          $request = null;
          $dbh = null;
        }
      }
      else
        header("Location: accountmanager.php?error");
  }
?>

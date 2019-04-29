<?php
session_start();
if (isset($_SESSION) && isset($_SESSION['id']))
  header('404.php');
else
{
    if (isset($_POST) && isset($_POST['newpasswd']) && isset($_POST['email']) && isset($_POST['newverifpasswd']) && isset($_POST['submit']) && $_POST['submit'] == 'Modify Password')
    {
      if (strlen($_POST['newpasswd']) < 6)
        $err = 1;
      if ($_POST['newpasswd'] != $_POST['newverifpasswd'])
      {
        $err = 1;
        header('Location: ');
      }
      $uppercase = preg_match('/[A-Z]/', $_POST['newpasswd']);
      $lowercase = preg_match('/[a-z]/', $_POST['newpasswd']);
      $number    = preg_match('/[0-9]/', $_POST['newpasswd']);
      $specialChars = preg_match('/[^\w]/', $_POST['newpasswd']);
      if(!$uppercase || !$lowercase || !$number || !$specialChars)
      {
        $err = 1;
        header('Location: ');
      }
      if (!isset($err))
      {
        $new_password = hash('whirlpool', $_POST['newpasswd']);
        require('config/database.php');
        $dbh = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pw = hash('whirlpool', $_POST['oldpasswd']);
        $request = $dbh->prepare('UPDATE `users` SET `password` = :new_password WHERE `email` = :email');
        $request->bindParam(':new_password', $new_password);
        $request->bindParam(':email', $_POST['email']);
        $request->execute();
        $request = null;
        $dbh = null;
        header("Location: identification.php?login");
        }
        $request = null;
        $dbh = null;
      }
    else
      header("Location: ");
 }
?>

<?php
session_start();
if (isset($_SESSION) && isset($_SESSION['id']))
  header('404.php');
function confirm($mail, $link, $username)
{
  $subject = 'Camagru - Account Activation';
  $message = '
      <html>
       <head>
        <title>Camagru - Account Activation</title>
       </head>
       <body>
       Welcome <b>'.$username.'</b> to Camagru !<br>
       The last step before fun is to validate your account ! Just click the link below !
       <br>
       <br>
       <a href="http://'.$link.'">Click Here to activate your account !</a>
       </body>
      </html>
      ';
      $headers[] = 'MIME-Version: 1.0';
      $headers[] = 'Content-type: text/html; charset=iso-8859-1';
      $headers[] = 'From: Camagru Account Service <account@camagru.com>';
      mail($mail, $subject, $message, implode("\r\n", $headers));
}

function password($mail, $link, $username)
{
  $subject = 'Camagru - Password Recovery';
  $message = '
      <html>
       <head>
        <title>Camagru - Password Recovery</title>
       </head>
       <body>
       Hi <b>'.$username.'</b> !<br>
       It appears that you forgot your password to log-in on Camagru !
       <br>
       To reset your password, just click the link below :) Have a nice day with us !
       <br>
       <a href="http://'.$link.'">Click Here to reset your password !</a>
       </body>
      </html>
      ';
      $headers[] = 'MIME-Version: 1.0';
      $headers[] = 'Content-type: text/html; charset=iso-8859-1';
      $headers[] = 'From: Camagru Account Service <account@camagru.com>';
      mail($mail, $subject, $message, implode("\r\n", $headers));
}

function notif($mail, $type, $to, $from)
{
  $subject = 'Camagru - Notification';
  $message = '
      <html>
       <head>
        <title>Camagru - Notification</title>
       </head>
       <body>
       Hi <b>'.$to.'</b> !<br>
       <b>'.$from.'</b> just '.$type.' your post !
       </body>
      </html>
      ';
      $headers[] = 'MIME-Version: 1.0';
      $headers[] = 'Content-type: text/html; charset=iso-8859-1';
      $headers[] = 'From: Camagru <notifications@camagru.com>';
      mail($mail, $subject, $message, implode("\r\n", $headers));
}

function username($mail, $username)
{
  $subject = 'Camagru - Username Recovery';
  $message = '
      <html>
       <head>
        <title>Camagru - Username Recovery</title>
       </head>
       <body>
       Hi !<br>
       It appears that you forgot your username to log-in on Camagru !
       <br>
          Username = '.$username.'
       <br>
       Have a nice day !
       </body>
      </html>
      ';
      $headers[] = 'MIME-Version: 1.0';
      $headers[] = 'Content-type: text/html; charset=iso-8859-1';
      $headers[] = 'From: Camagru Account Service <account@camagru.com>';
      mail($mail, $subject, $message, implode("\r\n", $headers));
}
if (isset($_POST) && !isset($_POST['type']))
  header('Location: 404.php');
else {
  if (isset($_POST) && isset($_POST['type']) && isset($_POST['email']) && $_POST['type'] == 'username')
  {
    require('config/database.php');
    $dbh = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $request = $dbh->prepare('SELECT `username` FROM `users` WHERE `email` = :mail');
    $request->bindParam(':mail', $_POST['email']);
    $request->execute();
    $res = $request->fetch(PDO::FETCH_ASSOC);
    if (!empty($res))
    {
      $mail = $_POST['email'];
      $username = $res['username'];
      username($mail, $username);
    }
    header('Location: identification.php?login');
  }
  if (isset($_POST) && isset($_POST['type']) && isset($_POST['email']) && $_POST['type'] == 'password')
  {
    require('config/database.php');
    $dbh = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $request = $dbh->prepare('SELECT `email` FROM `users` WHERE `email` = :mail');
    $request->bindParam(':mail', $_POST['email']);
    $request->execute();
    $res = $request->fetch(PDO::FETCH_ASSOC);
    if (!empty($res))
    {
      require('config/correction.php');
      $mail = $res['email'];
      $hashedmail = strtoupper(hash('whirlpool', $mail));
      $link = "$PATH_TO_WEBSITE/recover.php?pr=$hashedmail";
      password($mail, $link, $username);
    }
    header('Location: identification.php?login');
  }
}
?>

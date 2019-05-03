<?php
  session_start();
  if (isset($_SESSION) && isset($_SESSION['id']))
    header("Location: index.php");
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Account Activation</title>
    <link rel="shortcut icon" href="img/favicon.ico">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/identification.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
  </head>
  <body style="background: url('img/bg.png'); background-repeat: repeat;">
    <div class="container">
      <div class="logo">
      <a href="index.php"><img src="img/lsg.png" alt="Logo"></a>
      </div>
      <div class="rainbow" style="z-index: 1; position: relative; margin-top: 3vh; width:50vw; max-width: 750px; min-width: 305px; margin-left: 25vw; margin-right:25vw; height: 0.6vh;"></div>
      <div class="form">
        <?php
        if (isset($_GET) && isset($_GET['v']))
        {
            require('config/database.php');
            try {
                $dbh = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
                $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $request = 'SELECT `email`, `validated` FROM `users`';
                foreach ($dbh->query($request) as $result)
                {
                  if (isset($result['email']) && isset($result['validated']) && (strtoupper(hash('whirlpool', $result['email'])) == $_GET['v']) && $result['validated'] == 0)
                  {
                    $validation = 1;
                    $request = $dbh->prepare('UPDATE `users` SET `validated` = 1 WHERE `email` = :email');
                    $request->bindParam(':email', $result['email']);
                    $request->execute();
                  }
                }
                  $dbh = null;
                  $sth = null;
                  $request = null;
                } catch (PDOException $e) {
                print "Erreur !: " . $e->getMessage() . "<br/>";
                die();
            }
          }
          if (isset($validation)) {
            ?><h1 class="formtitle">Success ! 😀</h1>
            <h3>Your account has been activated ! You can now login !</h3> <br> <br>
            <?php
            header("Refresh: 7; URL=index.php");
          } else {
            ?><h1 class="formtitle" style="color:red;"><i class="fas fa-exclamation-circle"></i> Error ! 😱</h1>
            <h3>Something wrong appened !</h3> <br> <br>
            <?php
          }?>
      <a href="index.php"><h4><i class="fas fa-home"></i>Go Home</h4></a>
      </div>
    </div>
  </body>
</html>

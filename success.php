<?php
  session_start();
  if (isset($_SESSION) && isset($_SESSION['id']))
    header("Location: index.php");
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Success !</title>
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
        <h1 class="formtitle">Success ! 😀</h1><?php
        if (isset($_GET) && isset($_GET['register']))
        {?>
        <h3>✉️ We sent you an email with a validation link !<br>🤘 Please click it and you will be able to login !</h3>
        <?php }
        ?>
        <a href="index.php"><h4>Go Home</h4></a>
      </div>
    </div>
  </body>
</html>

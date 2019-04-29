<?php
session_start();
if (isset($_SESSION) && isset($_SESSION['id']))
  header("Location: index.php");
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Identification</title>
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
      <div class="form"><?php if (isset($_GET) && isset($_GET['login'])){ ?>
        <h1 class="formtitle">Hello There ! 😀</h1>
        <form action="login.php" method="post">
          <input type="text" name="username" placeholder="😊 Username" value="" required/>
          <a href="recover.php?type=username"><h4>Forgot your Username ?</h4></a>
          <input type="password" name="pw" placeholder="🔑 Password" value="" required/>
          <a href="recover.php?type=password"><h4>Forgot your Password ?</h4></a>
          <br>
          <input type="submit" name="submit" value="Login" />
          <h3>New to Camagru ?</h3><a href="identification.php?register"><h3>Register 😉</h3></a>
        </form><?php } else if (isset($_GET) && isset($_GET['register'])){ ?>
        <h1 class="formtitle">Welcome Aboard !</h1>
        <form action="register.php" method="post">
          <input type="text" name="username" placeholder="😊 Username" value="" required/>
          <br>
          <input type="text" name="email" placeholder="✉️ Email Address" value="" required/>
          <input type="password" name="pw" placeholder="🔑 Password" value="" required/>
          <input type="password" name="pwverif" placeholder="🔐 Password Verification" value="" required/>
          <br>
          <input type="date" name="birthday" placeholder="🎂" onchange="this.className=(this.value!=''?'has-value':'')" min="<?php $min = date('Y-m-d',strtotime(date("Y-m-d", mktime()) . " - 100 years")); echo $min;?>" max="<?php
      $max=date('Y-m-d',strtotime(date("Y-m-d", mktime()) . " - 18 years")); echo $max;?>" value="" required/>
      <br>
          <input type="submit" name="submit" value="Register" />
        </form>
        <h3>Already Member ?</h3><a href="identification.php?login"><h3>Login 😉</h3></a>
      </div><?php } else { header("Location: 404.php");}?>
    </div>
  </body>
</html>

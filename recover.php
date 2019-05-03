<?php
  session_start();
  if (isset($_SESSION) && isset($_SESSION['id']))
    header("Location: 404.php");
  if (isset($_GET) && isset($_GET['pr']))
  {
    require('config/database.php');
    $dbh = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $request = $dbh->prepare('SELECT `email` FROM `users`');
    $request->execute();
    $res = $request->fetchAll(PDO::FETCH_ASSOC);
    foreach($res as $mail)
    {
      if (strtoupper(hash('whirlpool', $mail['email'])) == $_GET['pr'])
      {
        $recovery = $mail['email'];
      }
    }
    $request = null;
    $res = null;
    $dbh = null;
  }
if ((isset($_GET) && !isset($_GET['type'])) && (isset($_GET) && !isset($_GET['pr'])))
  header("Location: 404.php");
else
{
  if ((isset($_GET['type']) && ($_GET['type'] != 'username' && $_GET['type'] != 'password')) && !isset($recovery))
    header("Location: 404.php");
}
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Account Recovery</title>
    <link rel="shortcut icon" href="img/favicon.ico">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/accmanager.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
    <style media="screen">
      *{
        overflow: hidden;
      }
    </style>
  </head>
  <body style="background: url('img/bg.png'); background-repeat: repeat;">
    <?php if (isset($_GET) && isset($_GET['type']) && $_GET['type'] == 'username') {?>
      <h1>You forget your username ? Don't worry ! We'll send it back to you !</h1>
      <h2>Type your email in the field below and click the button ! We will send you an email with your username 😉</h2>
      <form class="" action="sendmail.php" method="post">
        <input type="text" name="email" value="" placeholder="✉️ Enter your email here">
        <input type="hidden" name="type" value="username">
        <input type="submit" name="submit" value=" Let's get it back !">
      </form>
    <?php } else if (isset($_GET) && isset($_GET['type']) && $_GET['type'] == 'password') {?>
      <h1>You lost your password ? Don't worry ! You can reset it at any time !</h1>
      <h2>Type your email in the field below and click the button ! We will send you an email with a link to reset your password 😉</h2>
      <form class="" action="sendmail.php" method="post">
        <input type="text" name="email" value="" placeholder="✉️ Enter your email here">
        <input type="hidden" name="type" value="password">
        <input type="submit" name="submit" value=" Let's go !">
      </form>
    <?php } else if (isset($recovery)) {?>
      <form action="recoverpw.php" method="post">
        <h1>Password Modification</h1>
        <h2>Remember: Your password must contain at least 6 characters, with at least 1 lowercase character, 1 uppercase character, 1 number and 1 special char !</h2>
        <input type="password" name="newpasswd" value="" placeholder="🔑 New Password" required>
        <?php if (isset($_GET) && isset($_GET['error']) && $_GET['error'] == 'pc'){ ?>
          <h5 class='success'>Your password must contain at least 6 characters, with at least 1 lowercase character, 1 uppercase character, 1 number and 1 special char !</h5>
        <?php } ?>
        <input type="password" name="newverifpasswd" value="" placeholder="🔐 New Password (Verification)" required>
        <?php if (isset($_GET) && isset($_GET['error']) && $_GET['error'] == 'pm'){ ?>
          <h5 class='success'>Passwords don't match !</h5>
        <?php } ?>
        <input type="hidden" name="pr" value="<?php echo $_GET['pr']; ?>">
        <input type="hidden" name="email" value="<?php echo htmlspecialchars($recovery); ?>">
        <input type="submit" name="submit" value="Modify Password">
      </form>
    <?php } else
    header('Location: 404.php');?>
  </body>
</html>

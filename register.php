<?php
  session_start();
  if (isset($_SESSION) && isset($_SESSION['id']))
    header("Location: index.php");
//Check syntax of each field
  if (isset($_POST) && isset($_POST['username']) && isset($_POST['email']) && isset($_POST['pw']) && isset($_POST['pwverif']) && isset($_POST['birthday']) && isset($_POST['submit']) && $_POST['submit'] === 'Register')
  {
    //username: max 32chars, only letters and numbers, automatically converted to lowercase
    if (strlen($_POST['username']) == 0 || strlen($_POST['username']) > 32)
    {
      $error = 1;
      header("Location: identification.php?register=usernamelength");
    }
    if (preg_match('/^([a-z]|[A-Z]|[0-9])+$/', $_POST['username']) === 0)
    {
      $error = 1;
      header("Location: identification.php?register=usernamecontent");
    }
    if (!isset($error))
      $usr_username = strtolower($_POST['username']);
    //email: validity
    if (empty($_POST['email']) || filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) === false)
    {
      $error = 1;
      header("Location: identification.php?register=invalidemail");
    }
    else
      $usr_email = $_POST['email'];
    //Password: min 6chars, need 1 letter and 1 number at least
    if (strlen($_POST['pw']) < 6)
    {
      $error = 1;
      header("Location: identification.php?register=password");
    }
    if ($_POST['pw'] != $_POST['pwverif'])
    {
      $error = 1;
      header("Location: identification.php?register=passwordmatch");
    }
    $uppercase = preg_match('/[A-Z]/', $_POST['pw']);
    $lowercase = preg_match('/[a-z]/', $_POST['pw']);
    $number    = preg_match('/[0-9]/', $_POST['pw']);
    $specialChars = preg_match('/[^\w]/', $_POST['pw']);
    if(!$uppercase || !$lowercase || !$number || !$specialChars)
    {
      $error = 1;
      header("Location: identification.php?register=password");
    }
    if (!isset($error))
      $usr_password = hash('whirlpool', $_POST['pw']);
    //Convert birthdate to MYSQL format
    $usr_birthdate = date("Y-m-d", strtotime($_POST['birthday']));
  }
  else
    header('Location: 404.php');
require('config/database.php');
require('config/correction.php');
try {
    //Create Connection
    $dbh = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //Requests here
    $request = 'SELECT `username` FROM `users`';
    foreach ($dbh->query($request) as $result)
    {
      if (isset($result['username']) && isset($usr_username) && $result['username'] == $usr_username)
      {
        $error = 1;
        header('Location: identification.php?register=usernameexists');
      }
    }
    $request = 'SELECT `email` FROM `users`';
    foreach ($dbh->query($request) as $result)
    {
      if (isset($result['email']) && isset($usr_email) && $result['email'] == $usr_email)
      {
        $error = 1;
        header('Location: identification.php?register=emailexists');
      }
    }
    //If everything is ok, register the user
    if (!isset($error) && isset($usr_username) && isset($usr_email) && isset($usr_password) && isset($usr_birthdate))
    {
      $request = $dbh->prepare('INSERT INTO `users` (`username`, `password`, `email`, `birthdate`, `avatar`) VALUES (:usr_username, :usr_password, :usr_email, :usr_birthdate, :avatar)');
      $request->bindParam(':usr_username', $usr_username);
      $request->bindParam(':usr_password', $usr_password);
      $request->bindParam(':usr_email', $usr_email);
      $request->bindParam(':usr_birthdate', $usr_birthdate);
      $file = 'img/avatars/default.png';
      $request->bindParam(':avatar', $file);
      $request->execute();
      require('sendmail.php');
      $hashedmail = strtoupper(hash('whirlpool', $usr_email));
      $vlink = "$PATH_TO_WEBSITE/validate.php?v=$hashedmail";
      confirm($usr_email, $vlink, $usr_username);
      header("Location: success.php?register");
    }
    $dbh = null;
    $sth = null;
    $request = null;
    } catch (PDOException $e) {
    print "Erreur !: " . $e->getMessage() . "<br/>";
    die();
}
?>

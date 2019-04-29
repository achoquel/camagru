<?php
//Check syntax of each field
  session_start();
  if (isset($_SESSION) && isset($_SESSION['id']))
    header("Location: index.php");
  if (isset($_POST) && isset($_POST['username']) && isset($_POST['pw']) && $_POST['submit'] === 'Login')
  {
    require('config/database.php');
    require('config/correction.php');
    try {
      //Create Connection
      $dbh = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
      $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      //Requests here
      //Check for username unicity
      $request = 'SELECT `username`, `password`, `validated`, `user_id` FROM `users`';
      foreach ($dbh->query($request) as $result)
      {
        if (isset($result['username']) && $result['username'] == $_POST['username'])
        {
          if (isset($result['password']) && hash('whirlpool', $_POST['pw']) == $result['password'] && $result['validated'] == 1)
          {
            $logged = 1;
            $_SESSION['id'] = $result['user_id'];
            header('Location: index.php');
          }
          else if (isset($result['password']) && hash('whirlpool', $_POST['pw']) == $result['password'] && $result['validated'] == 0)
          {
            $logged = 0;
            header('Location: identification.php?login=validation');
          }
        }
      }
      $dbh = null;
      $sth = null;
      $request = null;
      if (!isset($logged))
        header('Location: identification.php?login=KO');
      } catch (PDOException $e) {
        print "Erreur !: " . $e->getMessage() . "<br/>";
        die();
      }
    }
    else {
      header("Location: 404.php");
    }
?>

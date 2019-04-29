<?php
session_start();
require('config/database.php');
if (isset($_POST) && isset($_POST['object']))
{
  if (strlen($_POST['object']) == 0)
  {
    header('Location: search.php');
  }
  else {
    try {
      $dbh = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
      $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $request = $dbh->prepare('SELECT `username`, `avatar` FROM `users` WHERE `username` LIKE :object');
      $request->execute(array(':object' => '%' . $_POST['object'] . '%'));
      $users = $request->fetchAll(PDO::FETCH_ASSOC);
      $_SESSION['search_users'] = $users;
      $request = $dbh->prepare('SELECT `post_id`, `us`.`username`, `pu`.`pref_private` AS `private`, `picture`, `description`
                                FROM `posts`
                                INNER JOIN `users` us ON `us`.`user_id` = `posts`.`user_id`
                                INNER JOIN `users` pu ON `pu`.`user_id` = `posts`.`user_id`
                                WHERE `description` LIKE :object');
      $request->execute(array(':object' => '%' . $_POST['object'] . '%'));
      $pics = $request->fetchAll(PDO::FETCH_ASSOC);
      $_SESSION['search_pics'] = $pics;
      $_SESSION['object'] = $_POST['object'];
      header('Location: search.php');
      } catch (PDOException $e) {
        print "Erreur !: " . $e->getMessage() . "<br/>";
        die();
      }
    }
  }
?>

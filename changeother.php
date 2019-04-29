<?php
session_start();
if (isset($_SESSION) && !isset($_SESSION['id']))
  header("Location: identification.php?login");
if (isset($_POST) && isset($_POST['submit']) && $_POST['submit'] == 'Save Changes')
{
  require('config/database.php');
  $dbh = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
  $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  if (isset($_POST['firstname']) && !empty($_POST['firstname']))
  {
    if (strlen($_POST['firstname']) > 42)
      $fnerr = 1;
    if (preg_match("/^[a-zA-ZàáâäãåąčćęèéêëėįìíîïłńòóôöõøùúûüųūÿýżźñçčšžÀÁÂÄÃÅĄĆČĖĘÈÉÊËÌÍÎÏĮŁŃÒÓÔÖÕØÙÚÛÜŲŪŸÝŻŹÑßÇŒÆČŠŽ∂ð ,.'-]+$/u", $_POST['firstname']) == 0)
      $fnerr = 2;
    if (!isset($fnerr))
    {
      $request = $dbh->prepare('UPDATE `users` SET `firstname` = :fn WHERE `user_id` = :id');
      $request->bindParam(':fn', $_POST['firstname']);
      $request->bindParam(':id', $_SESSION['id']);
      $request->execute();
    }
  }
  if (isset($_POST['lastname']) && !empty($_POST['lastname']))
  {
    if (strlen($_POST['lastname']) > 42)
      $lnerr = 1;
    if (preg_match("/^[a-zA-ZàáâäãåąčćęèéêëėįìíîïłńòóôöõøùúûüųūÿýżźñçčšžÀÁÂÄÃÅĄĆČĖĘÈÉÊËÌÍÎÏĮŁŃÒÓÔÖÕØÙÚÛÜŲŪŸÝŻŹÑßÇŒÆČŠŽ∂ð ,.'-]+$/u", $_POST['lastname']) == 0)
      $lnerr = 2;
    if (!isset($lnerr))
    {
      $request = $dbh->prepare('UPDATE `users` SET `lastname` = :lan WHERE `user_id` = :id');
      $request->bindParam(':lan', $_POST['lastname']);
      $request->bindParam(':id', $_SESSION['id']);
      $request->execute();
    }
  }
  if (isset($_POST['country']) && !empty($_POST['country']))
  {
    if (strlen($_POST['country']) > 42)
      $cerr = 1;
    if (preg_match("/^[a-zA-ZàáâäãåąčćęèéêëėįìíîïłńòóôöõøùúûüųūÿýżźñçčšžÀÁÂÄÃÅĄĆČĖĘÈÉÊËÌÍÎÏĮŁŃÒÓÔÖÕØÙÚÛÜŲŪŸÝŻŹÑßÇŒÆČŠŽ∂ð ,.'-]+$/u", $_POST['country']) == 0)
      $cerr = 2;
    if (!isset($cerr))
    {
      $request = $dbh->prepare('UPDATE `users` SET `country` = :country WHERE `user_id` = :id');
      $request->bindParam(':country', $_POST['country']);
      $request->bindParam(':id', $_SESSION['id']);
      $request->execute();
    }
  }
  if (isset($_POST['city']) && !empty($_POST['city']))
  {
    if (strlen($_POST['city']) > 42)
      $cierr = 1;
    if (preg_match("/^[a-zA-ZàáâäãåąčćęèéêëėįìíîïłńòóôöõøùúûüųūÿýżźñçčšžÀÁÂÄÃÅĄĆČĖĘÈÉÊËÌÍÎÏĮŁŃÒÓÔÖÕØÙÚÛÜŲŪŸÝŻŹÑßÇŒÆČŠŽ∂ð ,.'-]+$/u", $_POST['city']) == 0)
      $cierr = 2;
    if (!isset($cierr))
    {
      $request = $dbh->prepare('UPDATE `users` SET `city` = :city WHERE `user_id` = :id');
      $request->bindParam(':city', $_POST['city']);
      $request->bindParam(':id', $_SESSION['id']);
      $request->execute();
    }
  }
  if (isset($_POST['job']) && !empty($_POST['job']))
  {
    if (strlen($_POST['job']) > 42)
      $jerr = 1;
    if (preg_match("/^[a-zA-Z0-9àáâäãåąčćęèéêëėįìíîïłńòóôöõøùúûüųūÿýżźñçčšžÀÁÂÄÃÅĄĆČĖĘÈÉÊËÌÍÎÏĮŁŃÒÓÔÖÕØÙÚÛÜŲŪŸÝŻŹÑßÇŒÆČŠŽ∂ð ,.'-]+$/u", $_POST['job']) == 0)
      $jerr = 2;
    if (!isset($jerr))
    {
      $request = $dbh->prepare('UPDATE `users` SET `job` = :job WHERE `user_id` = :id');
      $request->bindParam(':job', $_POST['job']);
      $request->bindParam(':id', $_SESSION['id']);
      $request->execute();
    }
  }
  if (isset($_POST['mnotif']))
  {
    $request = $dbh->prepare('UPDATE `users` SET `pref_mail` = 1 WHERE `user_id` = :id');
    $request->bindParam(':id', $_SESSION['id']);
    $request->execute();
  }
  if (!isset($_POST['mnotif']))
  {
    $request = $dbh->prepare('UPDATE `users` SET `pref_mail` = 0 WHERE `user_id` = :id');
    $request->bindParam(':id', $_SESSION['id']);
    $request->execute();
  }
  if (isset($_POST['privatedata']))
  {
    $request = $dbh->prepare('UPDATE `users` SET `pref_private` = 1 WHERE `user_id` = :id');
    $request->bindParam(':id', $_SESSION['id']);
    $request->execute();
  }
  if (!isset($_POST['privatedata']))
  {
    $request = $dbh->prepare('UPDATE `users` SET `pref_private` = 0 WHERE `user_id` = :id');
    $request->bindParam(':id', $_SESSION['id']);
    $request->execute();
  }
  $request = null;
  $dbh = null;
  if (!isset($fnerr) && !isset($lnerr) && !isset($cerr) && !isset($cierr) && !isset($jerr))
    header("Location: accountmanager.php?success=other");
  else if (isset($fnerr))
    header("Location: accountmanager.php?error=fn");
  else if (isset($lnerr))
    header("Location: accountmanager.php?error=ln");
  else if (isset($cerr))
    header("Location: accountmanager.php?error=ce");
  else if (isset($cierr))
    header("Location: accountmanager.php?error=ci");
  else if (isset($jerr))
    header("Location: accountmanager.php?error=je");
}
?>

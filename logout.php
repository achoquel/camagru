<?php
session_start();
if (isset($_SESSION) && isset($_SESSION['id']))
{
  unset($_SESSION['id']);
  session_unset();
  session_destroy();
}
header('Location: index.php');
?>

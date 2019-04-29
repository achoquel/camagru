<?php
session_start();
if (isset($_SESSION) && !isset($_SESSION['id']))
  header("Location: identification.php?login");
// Get preferences state
require('config/database.php');
$dbh = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$request = 'SELECT `firstname`, `lastname`, `country`, `city`, `job`, `avatar`, `pref_mail`, `pref_private` FROM `users` WHERE `user_id` = "'.$_SESSION['id'].'"';
foreach ($dbh->query($request) as $result)
{
  if (isset($result['pref_mail']) && $result['pref_mail'] == 1)
    $pref_mail = 1;
  else
    $pref_mail = 0;
  if (isset($result['pref_private']) && $result['pref_private'] == 1)
    $pref_private = 1;
  else
    $pref_private = 0;
  if (isset($result['firstname']) && $result['firstname'] != null)
    $fn = $result['firstname'];
  if (isset($result['lastname']) && $result['lastname'] != null)
    $ln = $result['lastname'];
  if (isset($result['country']) && $result['country'] != null)
    $country = $result['country'];
  if (isset($result['city']) && $result['city'] != null)
    $city = $result['city'];
  if (isset($result['job']) && $result['job'] != null)
    $job = $result['job'];
  $avatar = $result['avatar'];
}
$request = null;
$dbh = null;
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Manage Account</title>
    <link rel="shortcut icon" href="img/favicon.ico">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/accmanager.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
  </head>
  <body style="background: url('img/bg.png'); background-repeat: repeat;">
    <div class="container">
      <div class="header">
        <a href="index.php"><div class="logo">
          <img src="img/lsg.png" alt="Logo">
        </div></a>
        <?php if (isset($_SESSION) && isset($_SESSION['id'])) {?>
      <a href="logout.php"><span class="logout"><i class="fas fa-times"></i></span></a>
    <?php } ?>
      </div>
      <div class="rainbow">
      </div>
      <div class="body">
        <h1>Account Manager</h1>
        <hr class="main">
        <h2>User Informations</h2>
        <hr class="sep">
        <table>
          <tr>
            <td>Email</td>
            <td>Password</td>
          </tr>
          <tr>
            <td><form class="" action="changemail.php" method="post">
              <input type="text" name="nemail" value="" placeholder="✉️ New Email" required>
              <input type="password" name="passwd" value="" placeholder="🔑 Password" required>
              <input type="submit" name="submit" value="Modify Email">
            </form></td>
            <td><form class="" action="changepw.php" method="post">
              <input type="password" name="oldpasswd" value="" placeholder="🗝 Old Password" required>
              <input type="password" name="newpasswd" value="" placeholder="🔑 New Password" required>
              <input type="password" name="newverifpasswd" value="" placeholder="🔐 New Password (Verification)" required>
              <input type="submit" name="submit" value="Modify Password">
            </form></td>
          </tr>
          <tr>
            <td>Username</td>
          </tr>
          <tr>
            <td><form class="" action="changeun.php" method="post">
              <input type="text" name="newusername" value="" placeholder="🤠 New Username" required>
              <input type="password" name="passwd" value="" placeholder="🔑 Password" required>
              <input type="submit" name="submit" value="Modify Username">
            </form></td>
          </tr>
        </table>
        <h2>Avatar</h2>
        <hr class="sep">
        <h2>Current Avatar:</h2>
        <img class='avatar' src="data:image/png;base64,<?php echo $avatar; ?>" alt="avatar">
        <br>
        <form action="avatarupload.php" method="post" enctype="multipart/form-data">
          <h2 class='select'>Select image to upload:</h2>
          <input type="file" name="fileToUpload" id="fileToUpload">
          <h3 class='recommendations'>Max file size: 2Mo. Recommended format: 200x200 px or higher.<br>
          If the picture is not a square, it might be deformed.</h3>
          <input type="submit" value="Set Avatar" name="submit">
        </form>
        <form class="" action="changeother.php" method="post">
        <h2>Personnal Information</h2>
        <hr class="sep">
        <input type="text" name="firstname" value="<?php if (isset($fn)) echo $fn; ?>" placeholder="First Name">
        <input type="text" name="lastname" value="<?php if (isset($ln)) echo $ln; ?>" placeholder="Last Name">
        <input type="text" name="country" value="<?php if (isset($country)) echo $country; ?>" placeholder="🏳️ Country">
        <input type="text" name="city" value="<?php if (isset($city)) echo $city; ?>" placeholder="🏘 City">
        <input type="text" name="job" value="<?php if (isset($job)) echo $job; ?>" placeholder="💼 Job">
        <h2>Preference</h2>
        <hr class="sep">
          <input type="checkbox" <?php if ($pref_mail == 1){ ?> checked="checked" value="" <?php } else {?>value="" <?php }?> name="mnotif" /> Send me an email for each notifications I receive.
          <br>
          <input type="checkbox" <?php if ($pref_private == 1){ ?> checked="checked" value="" <?php } else {?>value="" <?php }?> name="privatedata" value="0" /> Make my posts invisible for non-logged users.
          <br>
          <input type="submit" name="submit" value="Save Changes">
        </form>
      </div>
      <div class="footer">
        <a href="index.php"><span class="bbutton1"><i class="fas fa-home"></i></span></a>
        <a href="search.php"><span class="bbutton2"><i class="fas fa-search"></i></span></a>
        <a href="editor.php?type"><span class="bbutton3"><i class="fas fa-plus"></i></span></a>
        <a href="notifications.php"><span class="bbutton4"><i class="far fa-bell"></i></span></a>
        <a href="profile.php"><span class="bbutton5"><i class="fas fa-user"></i></span></a>
      </div>
    </div>
  </body>
</html>

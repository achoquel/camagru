<?php
require('database.php');
try {
    $dbh = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $request = $dbh->prepare('CREATE TABLE IF NOT EXISTS `comments` (
                                `comment_id` int(11) NOT NULL,
                                `user_id` int(11) DEFAULT NULL,
                                `post_id` int(11) DEFAULT NULL,
                                `comment` longtext
                              ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
                              CREATE TABLE IF NOT EXISTS `likes` (
                                `like_id` int(11) NOT NULL,
                                `user_id` int(11) DEFAULT NULL,
                                `post_id` int(11) DEFAULT NULL
                              ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
                              CREATE TABLE IF NOT EXISTS `notif` (
                                `notif_id` int(11) NOT NULL,
                                `from_user_id` int(11) DEFAULT NULL,
                                `type` int(11) DEFAULT NULL,
                                `to_user_id` int(11) DEFAULT NULL
                              ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
                              CREATE TABLE IF NOT EXISTS `posts` (
                                `post_id` int(11) NOT NULL,
                                `user_id` int(11) DEFAULT NULL,
                                `picture` longtext,
                                `description` longtext
                              ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
                              CREATE TABLE IF NOT EXISTS `users` (
                                `user_id` int(11) NOT NULL,
                                `username` text,
                                `email` longtext,
                                `password` longtext,
                                `firstname` longtext,
                                `lastname` longtext,
                                `birthdate` date DEFAULT NULL,
                                `country` longtext,
                                `city` longtext,
                                `job` longtext,
                                `avatar` longtext,
                                `validated` int(11) NOT NULL DEFAULT "0",
                                `pref_mail` int(11) NOT NULL DEFAULT "1",
                                `pref_private` int(11) NOT NULL DEFAULT "0"
                              ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
                              ALTER TABLE `comments`
                                ADD PRIMARY KEY (`comment_id`);
                              ALTER TABLE `likes`
                                ADD PRIMARY KEY (`like_id`);
                              ALTER TABLE `notif`
                                ADD PRIMARY KEY (`notif_id`);
                              ALTER TABLE `posts`
                                ADD PRIMARY KEY (`post_id`);
                              ALTER TABLE `users`
                                ADD PRIMARY KEY (`user_id`);
                              ALTER TABLE `comments`
                                MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT;
                              ALTER TABLE `likes`
                                MODIFY `like_id` int(11) NOT NULL AUTO_INCREMENT;
                              ALTER TABLE `notif`
                                MODIFY `notif_id` int(11) NOT NULL AUTO_INCREMENT;
                              ALTER TABLE `posts`
                                MODIFY `post_id` int(11) NOT NULL AUTO_INCREMENT;
                              ALTER TABLE `users`
                                MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT;');
      $request->execute();

} catch (PDOException $e) {
  print "Erreur !: " . $e->getMessage() . "<br/>";
  die();
}
header('Location: ../index.php');
?>

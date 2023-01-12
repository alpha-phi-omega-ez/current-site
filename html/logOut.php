<?php
include_once("/var/www/templates/functions/session_functions.php");

if (checkLoginStatus()) {
  logout();
  header('Location: home.php');
  die();
} else {
  header('Location: login.php');
  die();
}
?>

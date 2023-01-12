<?php
include_once("/var/www/templates/functions/session_functions.php");

startSession();
if (!checkLoginStatus()) {die();}

if ($_POST['type'] == "newType") {
  echo "<label for='newType'>Backwork Type</label><input type='Text' id='newType'></input>";
} else {
  echo "";
  die();
}
die();
?>
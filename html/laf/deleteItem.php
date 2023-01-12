<?php
// Include statements
include_once("/var/www/templates/functions/session_functions.php");
include_once("/var/www/templates/functions/database_functions.php");
include_once("/var/www/templates/functions/validate_info.php");

// Check if the user is logged in
startSession();
if (!checkLoginStatus()) {
  logout();
  header("Location: ../login.php");
  die();
}

// Attempt to connect to the database
if (!($conn = connectToDatabase("laf_database"))) {
  header("Location: ../errorPage.php");
  die();
}

// Validate Input
if (!validate_string_regex($_POST['id'], '/^[A-Z]{1}[0-9]{6}$/')) {
header("Location: ../errorPage.php");
  die();
} else {
  $sig = substr($_POST['id'], 0, 1);
  $id = ltrim($_POST['id'], "0ABCDEFGHIJKLMNOPQRSTUVWXYZ");
error_log($sig . "  " . $id);
}

// Remove the item from the database
$update = "UPDATE lafItem li LEFT JOIN type t ON t.typeId=li.typeId  SET li.active=0 WHERE li.itemId=? and t.letterSignifier=?";
$params = array($id, $sig);
$paramTypes = "ss";
if ($stmt = query($update, $conn, $paramTypes, $params)) {
header("Location: ../errorPage.php");
  die();
} else {
  header("Location: ../errorPage.php");
  die();
}
?>

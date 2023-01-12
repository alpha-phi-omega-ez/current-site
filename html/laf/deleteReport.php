<?php
// Include statements
include_once("/var/www/templates/functions/session_functions.php");
include_once("/var/www/templates/functions/database_functions.php");
include_once("/var/www/templates/functions/validate_info.php");

// Check if the user is logged in
startSession();
if (!checkLoginStatus()) {
  logout();
  header("Location: login.php");
  die();
}

// Attempt to connect to the database
if (!($conn = connectToDatabase("laf_database"))) {
  header("Location: /var/www/html/errorPage.php");
  die();
}

// Validate Input
if (!validate_string_regex($_POST['id'], '/^[A-Za-z0-9]{8}$/')) {
  die();
} else {
  $id = $_POST['id'];
}

// Remove the item from the database
$update = "UPDATE lostReport SET active=0 WHERE reportId=?";
if ($stmt = query($update, $conn, "s", array($id))) {
  die();
} else {
  header("Location: /var/www/html/errorPage.php");
  die();
}
?>

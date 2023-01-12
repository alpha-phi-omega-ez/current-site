<?php
include_once("/var/www/templates/functions/session_functions.php");
include_once("/var/www/templates/functions/database_functions.php");
include_once("/var/www/templates/functions/validate_info.php");

// Verify the user is logged in
startSession();
if (!checkLoginStatus()) {die();}

// Connect to the database
if (!($conn = connectToDatabase("test_database"))) {die();}

// Validate that the data was sent and is valid
if (!validate_string_regex($_POST["id"], "/[A-Za-z0-9]{8}/")) {
  die();
}
$id = $_POST["id"];
$date = date("Y-m-d" , strtotime("+1 week"));

$insert = "UPDATE backtest SET active=0, clearDate=? WHERE testId=?";
if ($stmt = query($insert, $conn, "ss", array($date, $id))) {
  echo "<div class='positive'>Test Id: " . htmlspecialchars($_POST['id']) . " has been deleted</div>";
  die();
} else {
  echo "<div class='negative'>Failed to delete backtest with Test Id: " . htmlspecialchars($_POST['id']) . "</div>";
  die();
}
?>
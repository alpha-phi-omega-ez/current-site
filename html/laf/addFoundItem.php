<?php
// Include functions;
include_once("/var/www/templates/functions/database_functions.php");
include_once("/var/www/templates/functions/validate_info.php");
include_once("/var/www/templates/functions/session_functions.php");

startSession();
if (!checkLoginStatus()) {
  header("Location: login.php");
  die();
}

if (!($conn = connectToDatabase("laf_database"))) {die();}

// Validate all variables were sent and store them in variables
if (validate_string_regex($_POST["type"], "/^[0-9]+$/")) {$typeId=$_POST["type"];} else {
  echo "<div class='negative'>Please select an item type<div>";
  die();
}
if (validate_string_regex($_POST["location"], "/^[0-9]+$/")) {$location=$_POST["location"];} else {
  echo "<div class='negative'>Please enter where the item was found<div>";
  die();
}
if (validate_date($_POST["date"])) {$foundDate=$_POST["date"];} else {
  echo "<div class='negative'>Make sure a date was entered<div>";
  die();
}
if (validate_string_regex($_POST["desc"], "/^[A-za-z0-9\s,.&$\-'?!\\/]{1,255}$/")) {$description=$_POST["desc"];} else {
  echo "<div class='negative'>Make sure the item description is filled out using only letters, numbers, and the following special characters ,&$-'.?\/!<div>";
  die();
}
if (validate_is_set($_POST["email"])) {
  if (filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
    $email=$_POST["email"];
  } else {
    echo "<div class='negative'>An invalid email was entered<div>";
    die();
  }
} else {
  $email = null;
}

// Ensure type and location exist in database
$find = "SELECT * FROM type t WHERE t.typeId=?";
if ($stmt = query($find, $conn, "s", array($typeId))) {
  $results = $stmt->get_result();
  if ($results->num_rows != 1) {
    header("Location: errorPage.php");
    die();
  } else {
    $row = $results->fetch_assoc();
    $sig = htmlspecialchars($row["letterSignifier"]);
  }
} else {
  header("Location: errorPage.php");
  die();
}
$find = "SELECT * FROM location l WHERE l.locationId=?";
if ($stmt = query($find, $conn, "s", array($location))) {
  $results = $stmt->get_result();
  if ($results->num_rows != 1) {die();}
} else {
  header("Location: errorPage.php");
  die();
}

// Add the found item to the database
$insert = "INSERT INTO lafItem (description, foundTime, typeId, locationId, ownerEmail) VALUES (?, ?, ?, ?, ?)";
if (!($stmt = query($insert, $conn, "sssss", array($description, $foundDate, $typeId, $location, $email)))) {
  echo "<div class='negative'>Failed to add item to database<div>";
  die();
} else {
  $newId = str_pad(htmlspecialchars($conn->insert_id), 6, "0", STR_PAD_LEFT);
  echo "<div class='positiveNoFade'>Item " . $sig . $newId . " has been added to database<div>";
}
?>

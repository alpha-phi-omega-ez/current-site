<?php
// Include statements
use function PHPSTORM_META\type;
include_once("/var/www/templates/functions/database_functions.php");
include_once("/var/www/templates/functions/validate_info.php");
include_once("/var/www/templates/functions/random_functions.php");

if (!($conn = connectToDatabase("laf_database"))){ // Create mySQL connection
  header("Location: errorPage.php");
  die();
}

// Validate all data and store it in variables
if (validate_is_set($_POST["firstName"])) {$firstName=$_POST["firstName"];} else {
  echo "<div class='negative'>Please enter your first name<div>";
  die();
}
if (validate_is_set($_POST["lastName"])) {$lastName=$_POST["lastName"];} else {
  echo "<div class='negative'>Please enter your last name<div>";
  die();
}
if (validate_is_set($_POST["email"]) && filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {$email=$_POST["email"];} else {
  echo "<div class='negative'>Please enter a valid email address<div>";
  die();
}
if (validate_string_regex($_POST["type"], "/^[0-9]*$/")) {$typeId=$_POST["type"];} else {
  echo "<div class='negative'>Please select an item type<div>";
  die();
}
if (validate_date($_POST["date"])) {$lostDate=$_POST["date"];} else {
  echo "<div class='negative'>Please select a date when the item was lost<div>";
  die();
}
if (validate_string_regex($_POST["desc"], "/^[A-za-z0-9\s,.&$\-'\"?!\\/]{1,100}$/")) {$description=$_POST["desc"];} else {
  echo "<div class='negative'>Make sure the item description is filled out using only letters, numbers, and the following special characters ,.?!<div>";
  die();
}
if (isset($_POST["locations"]) && is_array($_POST["locations"])) {
  $locations = $_POST["locations"];
  if (count($locations) > 0) {
    for ($i=0; $i<count($locations); $i++) {
      if (!validate_string_regex($locations[$i], "/^[0-9]{1,2}$/")) {
        echo "<div class='negative'>An invalid location was added<div>";
        die();
      }
    }
  }
} else {$locations = array();}

// Ensure locaions and itemType are in the database
$find = "SELECT * FROM type t WHERE t.typeId=?";
if ($stmt = query($find, $conn, "s", array($typeId))) {
  $results = $stmt->get_result();
  if ($results->num_rows != 1) {
    header("Location: errorPage.php");
    die();
  }
} else {
  header("Location: errorPage.php");
  die();
}
if (count($locations) > 0) {
  $find = "SELECT * FROM location l WHERE (";
  $types = "";
  $params = array();
  $adds = array();
  for ($i=0; $i<count($locations); $i++) {
    $types .= "s";
    $params[] = $locations[$i];
    $adds[] = "l.locationId=?";
  }
  $find .= implode(" OR ", $adds) . ")";
  if ($stmt = query($find, $conn, $types, $params)) {
    $results = $stmt->get_result();
    if ($results->num_rows != count($locations)) {die();}
  } else {
    header("Location: errorPage.php");
    die();
  }
}

// Generate a new ID for the report, and ensure it isn't already in use
$inDatabase = true;
while ($inDatabase) {
  $newId = random_str();
  $find = "SELECT * FROM lostReport WHERE reportId=?";
  if (!($stmt = query($find, $conn, "s", array($newId)))) {
    header("Location: errorPage.php");
    die();
  }
  $results = $stmt->get_result();
  if ($results->num_rows == 0) {
    $inDatabase = false;
  }
}

// Add the report to the database
$insert = "INSERT INTO lostReport (reportId, description, lostTime, typeId, ownerEmail, firstName, lastName) VALUES (?, ?, ?, ?, ?, ?, ?)";
$info = array($newId, $description, $lostDate, $typeId, $email, $firstName, $lastName);
if (!($stmt = query($insert, $conn, "sssssss", $info))) {
  header("Location: errorPage.php");
  die();
}

if (count($locations) > 0) {
  for ($i=0; $i<count($locations); $i++) {
    $insert = "INSERT INTO reportLocations (reportId, locationId) VALUES (?, ?)";
    if (!($stmt = query($insert, $conn, "ss", array($newId, $locations[$i])))) {
      header("Location: errorPage.php");
      die();
    }
  }
}

echo "<div class='positive'>Lost report has been submitted<div>";
?>
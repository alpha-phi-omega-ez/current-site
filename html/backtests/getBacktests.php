<?php
// Include functions
include_once("/var/www/templates/functions/session_functions.php");
include_once("/var/www/templates/functions/database_functions.php");
include_once("/var/www/templates/functions/validate_info.php");

startSession();

if (!($conn = connectToDatabase("test_database"))) {die();}

//make sure data was passed
if (validate_is_set($_POST['course']) && validate_is_set($_POST['subject'])) { // Check if input is from dropdowns
  $subject = $_POST['subject'];
  $course = $_POST['course'];

  //Verify that the subject code is valid
  if (!preg_match("/^[A-Za-z]{4}?/", $subject)) {
    die();
  }
  //Verify that the course code is valid
  if (!preg_match("/^[0-9]{4}$/", $course)) {
    echo "<p>Invalid Inputs</p>";
    die();
  }
} elseif (validate_is_set($_POST['info'])) {  // Check if info is from course link
  $info = $_POST['info'];
  // Verify info sent
  if (!preg_match("/[A-Z]{4}\-[0-9]{4}/", $info)) {
    die();
  }
  $info = explode("-", $info);
  $subject = $info[0];
  $course = $info[1];
} else {
  die();
}

$find = "SELECT t.typeName, b.typeNumber, b.testId, s.season, s.testYear ";
$find .= "FROM backtest b, semester s, course c, subjectCode k, testType t ";
$find .= "WHERE b.semesterId = s.semesterId AND b.courseId = c.courseId AND c.prefixId = k.prefixId AND b.typeId = t.typeId ";
$find .= "AND abbreviation = ? AND courseCode = ? AND active = 1 ";
$find .= "ORDER BY t.typeId ASC, b.typeNumber ASC, s.testYear ASC, s.season ASC";

$stmt = query($find, $conn, "ss", array($subject, $course));
$results = $stmt->get_result();

//Check if the results are empty
if ($results->num_rows > 0) {  
  $tests = "<div class='returnedWork'>";
  $tests .= "<h1>" . htmlspecialchars($subject) . " " . htmlspecialchars($course) . "</h1>";
  $lastType = "";
  $lastNumber = "";
  $start = true;
  while($row = $results->fetch_assoc()) {
    $type = htmlspecialchars($row['typeName']); //Escape HTML
    $number = htmlspecialchars($row['typeNumber']);
    $sem = htmlspecialchars($row['season']);
    switch ($sem) {
      case "f":
        $season = "Fall";
        break;
      case "s":
        $season = "Spring";
        break;
      case "u":
        $season = "Summer";
        break;
    }
    $year = htmlspecialchars($row['testYear']);
    $testId = htmlspecialchars($row["testId"]);
    if ($start) {
      $start = false;
      $lastType = $type;
      $lastNumber = $number;
      $tests .= "<div class='backtestDiv'><span class='bDivTitle'>" . $type . " " . $number . "</span><br>";
    }
    if ($lastType != $type) {
      $lastType = $type;
      $lastNumber = $number;
      $tests .= "</div><div class='backtestDiv'><span class='bDivTitle'>" . $type . " " . $number . "</span><br>";
    } elseif ($lastNumber != $number) {
      $lastNumber = $number;
      $tests .= "</div><div class='backtestDiv'><span class='bDivTitle'>" . $type . " " . $number . "</span><br>";
    }
    if (!isset($_SESSION['loggedIn']) || $_SESSION['loggedIn'] == FALSE) {
      $tests .= "<span>";
    } elseif ($_SESSION['loggedIn'] == True) {
      $tests .= "<span id='" . $testId . "'>";
    }
    $tests .= $season . ' ' . $year;
    if ($_SESSION['loggedIn'] == False || !isset($_SESSION['loggedIn'])) {
      $tests .= "</span><br>";
    } elseif ($_SESSION['loggedIn'] == True) {
      $tests .= "<input type='image' src='images/red_x.png' width='12' height='12' class='deleteTest' id='" .
                $testId . "'></input></span><br>";
    }
  }
  $tests .= "</div></div>";
  echo $tests;
} else {
  $noResult = "<div class='returnedWork'><h1>";
  $noResult .= $subject . " " . $course;
  $noResult .= "</h1><p>No backwork was found for this course</p></div>";
  echo $noResult;
  die();
}
?>
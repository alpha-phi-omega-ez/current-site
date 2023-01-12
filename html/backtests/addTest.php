<?php
// Include functions
include_once("/var/www/templates/functions/session_functions.php");
include_once("/var/www/templates/functions/database_functions.php");
include_once("/var/www/templates/functions/random_functions.php");
include_once("/var/www/templates/functions/validate_info.php");
use function PHPSTORM_META\type;

// Make sure the user is logged in
startSession();
if (!checkLoginStatus()) {die();}

if (!($conn = connectToDatabase("test_database"))) {die();}

// Validate that all data was sent
// Get all variables
$course = $_POST["course"];
$subject = $_POST["subject"];
$nSubjectName = ucwords($_POST["nSubjectName"]);
$nSubjectCode = strtoupper($_POST["nSubjectCode"]);
$nClassCode = ucwords($_POST["nClassCode"]);
$nClassName = $_POST["nClassName"];
$semester = $_POST["sem"];
$year = $_POST["y"];
$type = $_POST["t"];
$nType = ucwords($_POST["nType"]);
$number = $_POST["number"];

$variables = array($course, $subject, $semester, $year, $type, $number); // Add all variables for basic checks

foreach ($variables as $var) { // Make sure variables are set and not empty
  if (!validate_is_set($var)) {
    echo "<div class='negative'>Make sure all fields are filled out</div>";
    die();
  }
}

// Variable specific Checks
if ($subject != "newSubject") {
  if(!preg_match("/[A-Z]{4}/", $subject)) {
    echo "<div class='negative'>Invalid 4 Letter Subject Code</div>";
    die();
  }
} elseif (!(isset($nSubjectCode) && isset($nSubjectName) && preg_match("/[A-Z]{4}/", $nSubjectCode) && 
          $nSubjectName != "" && ctype_alnum(str_replace(" ", "", $nSubjectName)) && strlen($nSubjectName)<=100)) {
  echo "<div class='negative'>Please enter a valid 4 Letter Subject Code and Subject Name. Subject Names cannot contain special characters.</div>";
  die();
}
if ($course != "newCourse") {
  if (!preg_match("/[0-9]{4}/", $course)) {
    echo "<div class='negative'>Invalid 4 Digit Course Code</div>";
    die();
  }
} elseif (!(isset($nClassName) && isset($nClassCode) && preg_match("/[0-9]{4}/", $nClassCode) &&
          $nClassName != "" && ctype_alnum(str_replace(" ", "", $nClassName)) && strlen($nClassName)<=100)) {
  echo "<div class='negative'>Please enter a valid 4 Digit Course Code and Course Name. Course Names cannot contain special characters.</div>";
  die();
}
if ($semester != "spring" && $semester != "summer" && $semester != "fall") {
  echo "<div class='negative'>Invalid Semester selected</div>";
  die();
}
if (!preg_match("/^20[0-9]{2}/", $year) && $year <= date("Y")) {
  echo "<div class='negative'>Invalid year entered. Please enter a year between 2000 and " . date("Y") . "</div>";
  die();
}
if ($type != "newType") {
  if (!(ctype_alpha($type) && strlen($nType) <= 10)) {
    echo "<div class='negative'>Invalid Backwork Type</div>";
    die();
  }
} elseif (!(isset($nType) && $nType != "" && ctype_alpha($nType) && strlen($nType) <= 10)) {
  echo "<div class='negative'>Please enter a valid Backwork type. Only letters are allowed.</div>";
  die();
}
if (!(preg_match("/[0-9]+/", $number) && strlen($number) <= 2 && $number>=1)) {
  echo "<div class='negative'>Please enter a valid test number.</div>";
  die();
}

// Fetch existing courseId, prefixId, and/or typeId. If it's not in the database, return an error
if ($course != "newCourse") {
  $find = "SELECT c.courseId FROM course c INNER JOIN subjectCode s ON c.prefixId=s.prefixId WHERE abbreviation=? AND courseCode=?";
  $stmt = query($find, $conn, "ss", array($subject, $course));

  $results = $stmt->get_result();
  if ($results->num_rows != 1) {
    if ($results->num_rows == 0) {
      echo "<div class='negative'>No classes match" . htmlspecialchars($subject) . " " . htmlspecialchars($course) . "</div>";
      die();
    } else {
      echo "<div class='negative'>Fatal Error: Multiple classes match " . htmlspecialchars($subject) . " " . htmlspecialchars($course) . "</div>";
      die();
    }
  }
  $row = $results->fetch_assoc();
  $courseId = $row["courseId"];
} elseif ($subject != "newSubject") {
  $find = "SELECT s.prefixId FROM subjectCode s WHERE abbreviation=?";
  $stmt = query($find, $conn, "s", array($subject));

  $results = $stmt->get_result();
  if ($results->num_rows != 1) {
    if ($results->num_rows == 0) {
      echo "<div class='negative'>No subjects match " . htmlspecialchars($subject) . "</div>";
    } else {
      echo "<div class='negative'>Fatal Error: Multiple subjects match " . htmlspecialchars($subject) . "</div>";
      die();
    }
  }
  $row = $results->fetch_assoc();
  $prefixId = $row["prefixId"];
}
if ($type != "newType") {
  $find = "SELECT t.typeId FROM testType t WHERE typeName=?";
  $stmt = query($find, $conn, "s", array($type));

  $results = $stmt->get_result();
  if ($results->num_rows != 1) {
    if ($results->num_rows == 0) {
      echo "<div class='negative'>Couldn't find Backwork type " . htmlspecialchars($type) . "</div>";
      die();
    } else {
      echo "<div class='negative'>Fatal Error: Multiple Backwork Types match " . htmlspecialchars($type) . "</div>";
      die();
    }
  }
  $row = $results->fetch_assoc();
  $typeId = $row["typeId"];
}

// Make sure the new course, subject, or test type isn't already in the database
if ($subject == "newSubject") {
  $find = "SELECT s.prefixId FROM subjectCode s WHERE abbreviation=?";
  $stmt = query($find, $conn, "s", array($nSubjectCode));

  $results = $stmt->get_result();
  if ($results->num_rows != 0) {
    echo "<div class='negative'>The subject " . htmlspecialchars($nSubjectCode) . " already exists</div>";
    die();
  }
}
if ($course == "newCourse") {
  $find = "SELECT c.courseId FROM course c, subjectCode s WHERE c.prefixId=s.prefixId AND c.courseCode=? AND s.abbreviation=?";
  if ($subject == "newSubject") {
    $abbr = $nSubjectCode;
  } else {
    $abbr = $subject;
  }
  $stmt = query($find, $conn, "ss", array($nClassCode, $abbr));

  $results = $stmt->get_result();
  if ($results->num_rows != 0) {
    if ($subject == "newSubject") {
      echo "The course " . htmlspecialchars($nSubjectCode) . " " . htmlspecialchars($nClassCode) . " already exists";
    } else {
      echo "The course " . htmlspecialchars($subject) . " " . htmlspecialchars($nClassCode) . " already exists";
    }
    die();
  }
}
if ($type == "newType") {
  $find = "SELECT typeId FROM testType WHERE typeName=?";
  $stmt = query($find, $conn, "s", array($nType));

  $results = $stmt->get_result();
  if ($results->num_rows != 0) {
    echo "<div class='negative'>The Backwork Type " . htmlspecialchars($nType) . " already exists</div>";
    die();
  }
}

// Add new subjects, courses, and backwork types
if ($subject == "newSubject") {
  $insert = "INSERT INTO subjectCode (abbreviation, subjectName) VALUES (?, ?)";
  $stmt = query($insert, $conn, "ss", array($nSubjectCode, $nSubjectName));
  $prefixId = get_last_id($conn);
}
if ($course == "newCourse") {
  $insert = "INSERT INTO course (couseName, prefixId, courseCode) VALUES (?, ?, ?)";
  $stmt = query($insert, $conn, "sss", array($nClassName, $prefixId, $nClassCode));
  $courseId = get_last_id($conn);
}
if ($type == "newType") {
  $insert = "INSERT INTO testType (typeName) VALUES (?)";
  $stmt = query($insert, $conn, "s", array($nType));
  $typeId = get_last_id($conn);
}

// Check if the semester exists, and if not, add it
switch ($semester) {
  case "fall":
    $season = "f";
    break;
  case "spring":
    $season = "s";
    break;
  case "summer":
    $season = "u";
    break;
}
$find = "SELECT semesterId FROM semester WHERE testYear=? AND season=?";
$stmt = query($find, $conn, "ss", array($year, $season));
$results = $stmt->get_result();
if ($results->num_rows == 1) {
  $row = $results->fetch_assoc();
  $semesterId = $row["semesterId"];
} elseif ($results->num_rows == 0) {
  $insert = "INSERT INTO semester (testYear, season) VALUES (?, ?)";
  $stmt = query($insert, $conn, "ss", array($year, $season));
  $semesterId = get_last_id($conn);
} elseif ($results->num_rows > 1) {
  echo "<div class='negative'>Multiple semesters match the description " . htmlspecialchars($semester) . " " . htmlspecialchars($year) ."</div>";
  die();
}

// Generate a new testId and make sure it isn't already used
$inDatabase = 1;
while ($inDatabase) {
  $testId = random_str();
  $find = "SELECT * FROM backtest WHERE testId=?";
  $stmt = query($find, $conn, "s", array($testId));
  $results = $stmt->get_result();
  if ($results->num_rows == 0) {
    $inDatabase = 0;
  }
}

// Insert the test into the database
$insert = "INSERT INTO backtest (testId, semesterId, courseId, typeId, typeNumber, active) VALUES (?, ?, ?, ?, ?, 1)";
if ($stmt = query($insert, $conn, "sssss", array($testId, $semesterId, $courseId, $typeId, $number))) {
  echo "<div class='positive'>Added backtest to database</div>";
  die();
} else {
  echo "<div class='negative'>Failed to add backtest to database</div>";
  die();
}
?>

<?php
// Include statements
include_once("/var/www/templates/functions/database_functions.php");
include_once("/var/www/templates/functions/validate_info.php");

$split_length = 4;

if (!($conn = connectToDatabase("test_database"))) {die();}

//make sure data was passed
if (!validate_is_set($_POST['classFind'])) {
  die();
}


$find = "SELECT sc.abbreviation, c.courseCode, c.couseName FROM course c, subjectCode sc WHERE sc.prefixId = c.prefixId AND (";
$words = explode(" ", $_POST['classFind']);

$terms = array();

for ($i=0; $i<count($words); $i++) {
  $splits = str_split($words[$i], $split_length);
  for ($j=0; $j<count($splits); $j++) {
    if (strlen($splits[$j])>2) {
      $terms[] = $splits[$j];
    }
  }
}

$types = "";
$args = array();
$likes = array();

for ($i=0; $i<count($terms); $i++) {
  $likes[] = 'c.couseName LIKE CONCAT("%", ?, "%")';
  $types .= "s";
  $args[] = $terms[$i];
}
$ors = implode(" OR ", $likes);
$find .= $ors . ")";

$stmt = query($find, $conn, $types, $terms);

// Verify that results were recieved
$results = $stmt->get_result();
if ($results->num_rows < 1) {
  $noResult = "<div class='returnedWork'><h1>Showing Results for \"";
  $noResult .= htmlspecialchars($_POST["classFind"]);
  $noResult .= "\"</h1><p>No courses matching your search could be found</p></div>";
  echo $noResult;
  die();
}
$classes = $results->fetch_all(MYSQLI_ASSOC);

// Sort the rows by the levenshtein distance between the search and the courseName
if ($results->num_rows > 1) {
  $search = $_POST["classFind"];
  uasort($classes, function ($a, $b) use ($search) {
    $levA = levenshtein($search, $a["couseName"]);
    $levB = levenshtein($search, $b["couseName"]);

    return $levA === $levB ? 0 : ($levA > $levB ? 1 : -1);
  });
}

$courses = "<div class='returnedWork'>";
$courses .= "<h1>Showing Results for \"" . htmlspecialchars($_POST["classFind"]) . "\"</h1>";
foreach ($classes as $row) {
  $abbr = htmlspecialchars($row['abbreviation']); // Escape html
  $code = htmlspecialchars($row['courseCode']);
  $name = htmlspecialchars($row['couseName']);
  $courses .= "<div class='retunedClass'><input type='button' class='courseName' id='" . $abbr . 
              "-" . $code . "' value='" . $name . " (" . $abbr . " " . $code . ")'></input></div>";
}
$courses .= "</div>";
echo $courses;
die();
?>

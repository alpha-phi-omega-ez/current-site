<?php
// Include Functions
use function PHPSTORM_META\type;
include_once("/var/www/templates/functions/database_functions.php");
include_once("/var/www/templates/functions/validate_info.php");
include_once("/var/www/templates/functions/text_parsing_functions.php");
include_once("/var/www/templates/functions/session_functions.php");

startSession();
if (!checkLoginStatus()) {die();}

if (!($conn = connectToDatabase("laf_database"))) {die();} // Connect to the lostAndFound database

// Check what info is set, and validate it
if (isset($_POST['desc'])) {
  if (is_string($_POST['desc']) && validate_string_regex($_POST['desc'], "/^[A-za-z0-9\s,.&$-]{1,100}$/")) {
    $desc = $_POST['desc'];
  } else {
    die();
  }
} else {
  $desc = 0;
}
$locations = array();
if (isset($_POST['locations'])) {
  if (is_array($_POST['locations']) && count($_POST['locations']) > 0) {
    for ($i=0; $i<count($_POST['locations']); $i++) {
      if (!validate_string_regex($_POST['locations'][$i], "/^[0-9]+$/")) {
        die();
      }
    }
    $locations = $_POST['locations'];
  }
}
$types = array();
if (isset($_POST['types'])) {
  if (is_array($_POST['types']) && count($_POST['types']) > 0) {
    for ($i=0; $i<count($_POST['types']); $i++) {
      if (!validate_string_regex($_POST['types'][$i], "/^[0-9]+$/")) {
        die();
      }
    }
    $types = $_POST['types'];
  }
}
$foundOn = "";
$foundBefore = "";
$foundAfter = "";
if (validate_date($_POST['foundOn'])) {
  if (!(validate_date($_POST['foundBefore']) || validate_date($_POST['foundAfter']))) {
    $foundOn = $_POST['foundOn'];
  } else {
    die();
  }
} else {
  if (validate_date($_POST['foundBefore'])) {
    $foundBefore = $_POST['foundBefore'];
  }
  if (validate_date($_POST['foundAfter'])) {
    $foundAfter = $_POST['foundAfter'];
  }
  if ($foundBefore && $foundAfter && $foundBefore<$foundAfter) {
    die();
  }
}
$fName = "";
$lName = "";
if (isset($_POST['firstName'])) {
  if (is_string($_POST['firstName']) && validate_string_regex($_POST['firstName'], "/^[A-Za-z]{0,20}$/")) {
    $fName = $_POST['firstName'];
  } else {
    die();
  }
}
if (isset($_POST['lastName'])) {
  if (is_string($_POST['lastName']) && validate_string_regex($_POST['lastName'], "/^[A-Za-z]{0,20}$/")) {
    $lName = $_POST['lastName'];
  } else {
    die();
  }
}

// Start building WHERE statements for the query
$where = array();
$where[] = "temp.active=1";
$parameters = array();
$paramTypes = "";
if ($desc) { // Build description search
  // Prepare the description for search
  $text = removeStopWords($desc);
  $descriptors = preg_split('/\s+/', $text); // Split into individual words
  if (count($descriptors) < 1) {
    $thesSearch = "0";
    $greaterThan = 0.0;
    $relCheck = "temp.val>=? ";
  } else {
    // Establish search rules
    $matchPercent = 0.5; // An entry must match at least this percent of the description words to be considered
    $descCount = count($descriptors);
    $greaterThan = $descCount * $matchPercent;
    $descSearch = array();
    for ($i=0; $i<$descCount; $i++) {
      // Add a thesaurus check to the description search
      $descSearch[] = "(EXISTS (SELECT v.word FROM (SELECT d.wordId, d.word FROM dictionary d UNION SELECT t.wordId, t.word FROM thesaurus t) v WHERE v.wordId IN (SELECT d.wordId FROM dictionary d WHERE d.word=? UNION SELECT t.wordId FROM thesaurus t WHERE t.word=?) AND lr.description LIKE CONCAT('%', v.word, '%')) OR lr.description LIKE CONCAT('%', ?, '%'))"; 
      array_push($parameters, $descriptors[$i], $descriptors[$i], $descriptors[$i]);
      $paramTypes .= 'sss';
    }
    $thesSearch = "(";
    $thesSearch .= implode(" + ", $descSearch);
    $thesSearch .= ")";
    $relCheck = "temp.val>=? ";
  }
} else {
  $thesSearch = "0";
  $greaterThan = 0.0;
  $relCheck = "temp.val>=? ";
}

if ($types) { // Build types search
  $typeSearch = array();
  for ($i=0; $i<count($types); $i++) {
    $typeSearch[] = "temp.typeId=?";
    $parameters[] = $types[$i];
    $paramTypes .= "s";
  }
  $searchTypes = "(" . implode(" OR ", $typeSearch) . ")";
  $where[] = $searchTypes;
}

if ($foundOn) { // Build dates search
  $where[] = "temp.lostTime=?";
  $parameters[] = $foundOn;
  $paramTypes .= "s";
} else {
  if ($foundAfter) {
    $where[] = "temp.lostTime>=?";
    $parameters[] = $foundAfter;
    $paramTypes .= "s";
  }
  if ($foundBefore) {
    $where[] = "temp.lostTime<=?";
    $parameters[] = $foundBefore;
    $paramTypes .= "s";
  }
}

if ($fName) { // Build First Name search
  $where[] = "temp.firstName=?";
  $parameters[] = $fName;
  $paramTypes .= "s";
}
if ($lName) { // Build Last Name search
  $where[] = "temp.lastName=?";
  $parameters[] = $lName;
  $paramTypes .= "s";
}

// Build Location WHERE statement for query

if ($locations) { // Build locations search NEEDS TO BE FIXED
  $locSearch = array();
  for ($i=0; $i<count($locations); $i++) {
    $locSearch[] = "locs.locationId=?";
    $parameters[] = $locations[$i];
    $paramTypes .= "s";
  }
  $locQuery = "(NOT EXISTS (SELECT * FROM reportLocations rl WHERE rl.reportId=lr.reportId) OR EXISTS ";
  $locQuery .= "(SELECT * FROM (SELECT rl.locationId FROM reportLocations rl) locs WHERE ";
  $locQuery .= implode(" OR ", $locSearch) . ")) ";
} else {
  $locQuery = "";
}

// Assemble search query NEEDS TO BE FIXED
$search = "SELECT * FROM ( SELECT *, " . $thesSearch . " AS val FROM lostReport lr";
if ($locQuery) {
  $search .= " WHERE " . $locQuery . ") ";
} else {
  $search .= ") ";
}
$search .= "temp INNER JOIN type ty ON ty.typeId=temp.typeId WHERE ";
if (count($where) > 0) {
  $whereStatemnt = implode(" AND ", $where);
  $search .= $whereStatemnt . " AND ";
}
$search .= $relCheck;
$parameters[] = $greaterThan;
$paramTypes .= "d";
$search .= "ORDER BY temp.val DESC, temp.lostTime Desc LIMIT 50";

if (!($stmt = query($search, $conn, $paramTypes, $parameters))) {
  die();
} else {
  $results = $stmt->get_result();
  if ($results->num_rows > 0) {
    while ($row = $results->fetch_assoc()) {
      $resultDesc = htmlspecialchars($row['description']);
      $resultTime = htmlspecialchars($row['lostTime']);
      $resultType = htmlspecialchars($row['itemType']);
      $resultName = htmlspecialchars($row['firstName']) . " " . htmlspecialchars($row['lastName']);
      $resultMail = htmlspecialchars($row['ownerEmail']);
      $resultId = htmlspecialchars($row['reportId']);
      echo "<tr class='lostResults'><td>" . $resultType . "</td><td>" . $resultTime . "</td><td>" . $resultName ."</td><td>" . $resultMail . "</td><td>" . $resultDesc . "</td>";
      echo "<td><input type='image' src='images/red_x.png' width='12' height='12' class='deleteReport' id='" . $resultId . "'></tr>";
    }
  } else {
    echo "<tr class='lostResults' ><td colspan='4'>No items matched your search</td></tr>";
  }
}
?>
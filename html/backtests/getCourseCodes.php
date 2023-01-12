<?php
include_once("/var/www/templates/functions/database_functions.php");
include_once("/var/www/templates/functions/validate_info.php");

if (!($conn = connectToDatabase("test_database"))) {die();}

//Make sure the subject code was passed
if (validate_is_set($_POST['subject'])) {
  //Verify that the subject code is valid
  if (!preg_match("/^[A-Za-z]{4}?/", $_POST['subject'])) {
    die();
  }

  //Find all courses with the selected subject code
  $subject = $_POST['subject'];
  $find = "SELECT c.couseName, c.courseCode FROM course c, subjectCode s WHERE c.prefixId = s.prefixId and s.abbreviation = ? ORDER BY c.courseCode ASC";
  $stmt = query($find, $conn, "s", array($subject));
  $results = $stmt->get_result();

  //If the subject code has courses entered, generate an options menu
  if ($results->num_rows > 0) {
    $options = '<select name="courseCode" id="courseCode">';
    while($row = $results->fetch_assoc()) {
      $code = htmlspecialchars($row['courseCode'], ENT_QUOTES, 'utf-8'); //Escape HTML
      $name = htmlspecialchars($row['couseName'], ENT_QUOTES, 'utf-8');
      $options .= '<option value="' . $code . '">' . $code . ' - ' . $name . '</option>';
    }
    $options .= '</select><input id="findBacktest" value="Search" type="button" class="searchTest"></input>'; //echo the resulting html
    echo $options;
  } else { //Otherwise, echo an error
    die();
  }
} else {
  die();
}
?>

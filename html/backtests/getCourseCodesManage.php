<?php
include_once("/var/www/templates/functions/session_functions.php");
include_once("/var/www/templates/functions/database_functions.php");
include_once("/var/www/templates/functions/validate_info.php");

startSession();
if (!checkLoginStatus()) {die();}

if (!($conn = connectToDatabase("test_database"))) {die();}

//Make sure the subject code was passed
if (validate_is_set($_POST['subject'])) {
  //Check if the user is trying to add a New subject
  if ($_POST['subject'] == "newSubject") {
    echo "<div class='addSubject'>
            <label for='newSubjectName'>Subject Name</label><input type='Text' id='newSubjectName'></input>
            <label for='newSubjectCode'>4 Letter Subject Code</label><input type='Text' id='newSubjectCode'></input>
          </div>
          <div class='addCourse'> 
            <label for='newClassCode'>4 Digit Class Id</label><input type='Text' id='newClassCode'></input>
            <label for='newClassName'>Course Name</label><input type='Text' id='newClassName'></input>
          </div>";
    die();
  }
  
  //verify that the subject code is valid
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
    $options = '<select name="sCourseCode" id="sCourseCode">';
    while($row = $results->fetch_assoc()) {
      $code = htmlspecialchars($row['courseCode']); //Escape HTML
      $name = htmlspecialchars($row['couseName']);
      $options .= '<option value="' . $code . '">' . $code . ' - ' . $name . '</option>';
    }
    $options .= "<option value='newCourse'>New Course</option></select>";
    echo $options; //echo the resulting html
  } else { //Otherwise, echo an error
    die();
  }
} else {
  die();
}
?>

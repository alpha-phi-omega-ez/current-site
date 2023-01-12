<?php
include_once("/var/www/templates/functions/session_functions.php");

startSession();
if (!checkLoginStatus()) {die();}

if ($_POST['course'] == "newCourse") {
  echo "<div class='addCourse'>
          <label for='newClassCode'>4 Digit Class Code</label><input type='Text' id='newClassCode'></input>
          <label for='newClassName'>Class Name</label><input type='Text' id='newClassName'></input>
        </div>";
} else {
  echo "";
  die();
}
die();
?>
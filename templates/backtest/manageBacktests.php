<?php
include_once("/var/www/templates/functions/session_functions.php");
include_once("/var/www/templates/functions/database_functions.php");

startSession();

//Verify the user is logged in
if (!checkLoginStatus()) {header("Location: login.php"); die();}
?>
<head>
  <link rel="stylesheet" href="/css/manageTests.css">
</head>
<div class='manageTests'>
  <h1>Add Backtest</h1>
  <form autocomplete="off" onSubmit="return false;" id="testsForm"> 
    <div class='testCourse'>
      <select name="sSubjectCode" id="sSubjectCode">
        <option value="" selected disabled hidden>----</option>
        <?php
          //Attempt to connect to the database
          if (!($conn = connectToDatabase("test_database"))) {die();}

          $find = "SELECT abbreviation FROM subjectCode ORDER BY abbreviation ASC";
          $stmt = query($find, $conn);
          $results = $stmt->get_result();

          if ($results->num_rows == 0) {
            header("Location: errorPage.php");
            die();
          } else {
            while ($row = $results->fetch_assoc()) {
              $abbr = htmlspecialchars($row["abbreviation"]);
              echo '<option value="' .  $abbr . '">' . $abbr . "</option>";
            }
          }
        ?>
        <option value="newSubject">New Subject</option> 
      </select>
      <div id="splace"></div>
      <div id="shere"></div>
    </div>
    <div class='testTime'>
      <div class='seasonRadios'>
        <div class='fallRadio'>
          <input type="Radio" id="fall" name="semester" value="fall"></input>
          <label for="fall">Fall</label>
        </div>
        <div class='springRadio'>
          <input type="Radio" id="spring" name="semester" value="spring"></input>
          <label for="spring">Spring</label>
        </div>
        <div class='summerRadio'>
          <input type="Radio" id="summer" name="semester" value="summer"></input>
          <label for="summer">Summer</label>
        </div>
      </div>
      <div class='testYear'>
        <label for="year">Year</label>
        <input type="Text" id="year" name="year"></label>
      </div>
    </div>
    <div class='testType'>
      <select name="type" id="type">
        <?php
        $find = "SELECT typeName FROM testType";
        $stmt = query($find, $conn);
        $results = $stmt->get_result();

        if ($results->num_rows == 0) {
          header("Location: errorPage.php");
          die();
        } else {
          while ($row = $results->fetch_assoc()) {
            $typeName = htmlspecialchars($row["typeName"]);
            echo '<option value="' . $typeName . '">' . $typeName . "</option>";
          }
        }
        ?>
        <option value='newType'>Other</option>
      </select>
      <span id="otherType"></span>
      <label for="testNumber">Backwork Number</label>
      <input type="Text" id="testNumber" name="testNumber"></input>
    </div>
    <input type="button" id="addTest" name="addTest" value="Add Test"></input>
  </form>
</div>
<div id="feedback"></div>

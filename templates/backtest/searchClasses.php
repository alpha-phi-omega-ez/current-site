<?php
include_once("/var/www/templates/functions/database_functions.php");
include_once("/var/www/templates/functions/session_functions.php");

startSession();
?>
<div class="backContainer">
  <div class="dropTitle">
    <h1>Search by Subject</h1>
  </div>
  <div class="searchTitle">  
    <h1>Search by Course Name</h1>
  </div>
  <div class="dropContainer">
    <form autocomplete="off" class="backDrop">
      <label for="subjectCode">Subject Code</label>
      <select name="subjectCode" id="subjectCode">
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
      </select>
      <br>
      <label for="courseId">Course Code</label>
      <span id="place">
        <select name="courseCode" id="courseCode">
          <option value="" selected disabled hidden>----</option>
        </select>
        <input type="Button" value="Search"></input>
      </span>
    </form>
  </div>

  <div class="searchContainer">
    <form autocomplete="off" class="backSearch">
      <input name="classSearch" id="classSearch" type="text"></input>
      <input name="searchClasses" id="searchClasses" type="Button" value="Search"></input>
    </form>
  </div>
</div>
<div id="here"></div>

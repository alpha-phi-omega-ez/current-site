<?php
include_once("/var/www/templates/functions/database_functions.php");
if (!($conn = connectToDatabase("laf_database"))){header('Location: errorPage.php');die();}
?>
<div class='report'>
  <h1>Report Lost Item</h1>
  <form autocomplete='off' onsubmit="return false;" id="lostReportForm">
    <div class='name'>
      <label for='firstName'>First Name</label>
      <input type='text' id='firstName'>
      <label for='lastName'>Last Name</label>
      <input type='text' id='lastName'>
    </div>
    <div class='emailDiv'>
      <label for='email'>email</label>
      <input type='text' id='email'>
    </div>
    <div class='locationsDiv'>
      <label for='locations'>Possible Locations</label>
      <span></span>
      <select id="locations">
        <?php
          $find = "SELECT l.locationId, l.locationName FROM location l ORDER BY SUBSTR(l.locationName, 1, 1)";
          $stmt = query($find,$conn);
          $results = $stmt->get_result();

          if ($results->num_rows > 0) {
            while($row=$results->fetch_assoc()) {
              $location = htmlspecialchars($row['locationName']);
              $locationId = htmlspecialchars($row['locationId']);
              $option = "<option id='${locationId}' name='${location}'>${location}</option>";
              echo $option;
            }
          } else {
            die();
          }
        ?>
      </select>
      <input type='button' id='addLocation' value="Add Location">
      <div class='reportLocations'>
        <div class='selectedLocations'></div>
        <div class='emtpyLocations'>Locations you add will appear here. You can have no locations, or as many as you want.</div>
      </div>
    </div>
    <div class='typeDiv'>
      <label for='itemType'>Item Type</label>
      <select id='itemType'>
        <?php
          $find = "SELECT t.typeId, t.itemType FROM type t";
          $stmt = query($find, $conn);
          $results = $stmt->get_result();

          if ($results->num_rows > 0) {
            while ($row=$results->fetch_assoc()) {
              $type = htmlspecialchars($row['itemType']);
              $id = htmlspecialchars($row['typeId']);
              $option = "<option id='${id}' class='${type}'>${type}</option>";
              echo $option;
            }
          } else {
            die();
          }
        ?>
      </select>
    </div>
    <div class='dateDiv'>
      <label for='lostDate'>Date Lost</label>
      <?php
        $lostDate = "<input type='date' id='lostDate' min='2000-01-01' max='" . date("Y-m-d") ."'>";
        echo $lostDate;
      ?>
    </div>
    <div class='descDiv'>
      <label for='description'>Description</label>
      <textarea id='description'></textarea>
    </div>
    <input type='button' id='submitReport' value='Submit'>
  </form>
</div>

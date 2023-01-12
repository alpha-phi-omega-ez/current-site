<?php
include_once("/var/www/templates/functions/database_functions.php");
if (!($conn = connectToDatabase("laf_database"))){header('Location: errorPage.php');die();}
?>

<div class='found'>
  <h1>Report Found Item</h1>
  <form autocomplete="off" onsubmit="return false;" id="foundItemForm">
    <label for='lafType'>Item Type</label>
    <select id='lafType'>
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
    <label for='foundLocations'>Location Found</label>
    <select id="foundLocations">
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
    <label for='foundDate'>Date Found</label>
    <?php
      $lostDate = "<input type='date' id='foundDate' min='2000-01-01' max='" . date("Y-m-d") ."'>";
      echo $lostDate;
    ?>
    <label for='itemDescription'>Description</label>
    <textarea id='itemDescription'></textarea>
    <label for='possibleOwner'>Possible Owner email</label>
    <input type='text' id='possibleOwner'>
    <input type='button' id='submitItem' value='Submit'>
  </form>
</div>

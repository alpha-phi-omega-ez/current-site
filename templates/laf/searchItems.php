<?php
if (!($conn = connectToDatabase('laf_database'))) {die();}
?>
<div class='searchItems'>
  <h1>Search Found Items</h1>
  <h2>Search Options:</h2>
  <form autocomplete="off" id="foundForm">
    <div id='tagContainer'>
      <div class='tagSection' id='typeTag'>
        <select id='lafTypes'><?php
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
        <input type='button' class='addTag' id='addTypeTag' value='Add Tag'>
      </div>
      <div class='tagSection' id='placeTag'>
        <select id='foundPlace'>
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
          <input type='button' class='addTag' id='addPlaceTag' value='Add Tag'>
        </select>
      </div>
      <div class='tagSection' id='dateTag'>
        <label for='latestDate'></label>
        <select id='dateType'>
          <option id='foundAfter'>Found After</option>
          <option id='foundBefore'>Found Before</option>
          <option id='foundOn'>Found On</option>
        </select>
        <?php
          $today = date('Y-m-d');
          $lostDate = "<input type='date' id='latestDate' min='2000-01-01' max='${today}' value='${today}'>";
          echo $lostDate;
        ?>
        <input type='button' class='addTag' id='addDateTag' value='Add Tag'>
      </div>
      <div class='tagSection' id='descTag'>
        <label for='descSearch'>Description</label>
        <textarea id='descSearch'></textarea>
        <input type='button' class='addTag' id='addDescTag' value='Add Tag'>
      </div>
    </div>
    <div class='addedTagsContainer'>
      <div class='addedFoundTags'></div>
      <div class='emptyTagsText'>Search tags will appear here when you add them.</div>
    </div>
    <input type='button' id='searchFoundTags' value='Search'>
  </form>
  <div class='searchTableContainer'>
    <table class='foundSearchResults'>
      <tr>
        <th>Item Id</th>
        <th>Item Type</th>
        <th>Found Date</th>
        <th>Found Location</th>
        <th>Description</th>
        <th></th>
      </tr>
      <?php
      $find = "SELECT * FROM lafItem li INNER JOIN location l ON l.locationId=li.locationId INNER JOIN type t ON t.typeId=li.typeId WHERE li.active=1 ORDER BY li.foundTime DESC LIMIT 50";
      if (!($stmt = query($find, $conn))) {
        die();
      } else {
        $results = $stmt->get_result();
        if ($results->num_rows > 0) {
          while ($row = $results->fetch_assoc()) {
            $id = str_pad(htmlspecialchars($row['itemId']), 6, "0", STR_PAD_LEFT);
            $sig = htmlspecialchars($row['letterSignifier']);
            $idRow = $sig . $id;
            $resultDesc = htmlspecialchars($row['description']);
            $resultTime = htmlspecialchars($row['foundTime']);
            $resultLocation = htmlspecialchars($row['locationName']);
            $resultType = htmlspecialchars($row['itemType']);
            echo "<tr class='foundResults'><td>" . $idRow . "</td><td>" . $resultType . "</td><td>" . $resultTime . "</td><td>" . $resultLocation ."</td><td>" . $resultDesc . "</td>";
            echo "<td><input type='image' src='images/red_x.png' width='12' height='12' class='deleteItem' id='" . $idRow . "'></td></tr>";
          }
        } else {
          echo "<tr class='foundResults' ><td colspan='4'>Error loading results</td></tr>";
        }
      }
      ?>
    </table>
  </div>
</div>
<?php
if (!($conn = connectToDatabase('laf_database'))) {die();}
?>
<div class='searchReports'>
  <h1>Search Lost Reports</h1>
  <h2>Search Options:</h2>
  <form autocomplete="off" id="reportForm">
    <div id='tagContainer'>
      <div class='tagSection' id='nameTagF'>
        <select class='nameTagOption'>
          <option name='firstNameTag'>First Name</option>
          <option name='lastNameTag'>Last Name</option>
        </select>
        <input type='text' id='nameTagText'>
        <input type='button' class='addTag' id='addNameTag' value='Add Tag'>
      </div>
      <div class='tagSection' id='typeTagF'>
        <select id='lafTypesF'><?php
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
        <input type='button' class='addTag' id='addTypeTagF' value='Add Tag'>
      </div>
      <div class='tagSection' id='placeTagF'>
        <select id='foundPlaceF'>
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
          <input type='button' class='addTag' id='addPlaceTagF' value='Add Tag'>
        </select>
      </div>
      <div class='tagSection' id='dateTagF'>
        <label for='latestDateF'></label>
        <select id='dateTypeF'>
          <option id='foundBefore'>Found Before</option>
          <option id='foundAfter'>Found After</option>
          <option id='foundOn'>Found On</option>
        </select>
        <?php
          $today = date('Y-m-d');
          $lostDate = "<input type='date' id='latestDateF' min='2000-01-01' max='${today}' value='${today}'>";
          echo $lostDate;
        ?>
        <input type='button' class='addTag' id='addDateTagF' value='Add Tag'>
      </div>
      <div class='tagSection' id='descTagF'>
        <label for='descSearchF'>Description</label>
        <textarea id='descSearchF'></textarea>
        <input type='button' class='addTag' id='addDescTagF' value='Add Tag'>
      </div>
    </div>
    <div class='addedTagsContainer'>
      <div class='addedLostTags'></div>
      <div class='emptyTagsText'>Search tags will appear here when you add them.</div>
    </div>
    <input type='button' id='searchFoundTagsF' value='Search'>
  </form>
  <div class='searchTableContainer'>
    <table class='lostSearchResults'>
      <tr>
        <th>Item Type</th>
        <th>Found Date</th>
        <th>Owner Name</th>
        <th>Owner email</th>
        <th>Description</th>
        <th></th>
      </tr>
      <?php
      $find = "SELECT * FROM lostReport lr INNER JOIN type t ON t.typeId=lr.typeId WHERE lr.active=1 ORDER BY lr.lostTime DESC LIMIT 50";
      if (!($stmt = query($find, $conn))) {
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
          echo "<tr class='lostResults'><td colspan='4'>Error loading results</td></tr>";
        }
      } 
      ?>
    </table>
  </div>
</div>
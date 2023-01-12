<?php
include_once("/var/www/templates/functions/session_functions.php");
startSession();
$loggedIn = checkLoginStatus();
?>
<html>
  <head>
    <title>Lost and Found | Alpha Phi Omega - Epsilon Zeta Chapter</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src='scripts/publicLafReport.js'></script>
    <?php
      if ($loggedIn) {
        echo "<script src='scripts/lafManagement.js'></script>";
        echo "<link rel='stylesheet' href='css/laf.css'>";
      } else {
        echo "<link rel='stylesheet' href='css/lafForm.css'>";
      }
    ?>
  </head>

  <?php require_once("../templates/navbar.php")?>
  <body>
    <?php if (!$loggedIn) {?>
    <div id="main">
      <div class="col">
    <?php }?>
        <?php if (!$loggedIn) {
          echo '<h1>RPI Lost and Found</h1>
                <p>Our chapter of Alpha Phi Omega has been running the campus-wide lost and found since our founding in 1947. We check most buildings on campus once a week, and all of the lost items are logged into our computer system and put into our lost and found.</p>
                <p>If you have either lost or found something on campus, or if you would like to check the buildings for yourself, please stop by our office (Union 3420) and we&rsquo;ll be glad to assist you.</p>';
        }?>
        <div class='lafContainer'>
          <?php
            if ($loggedIn) {
              require_once('../templates/laf/lafMenu.php');
            }
          ?>
          <div class='tabContainer'>
            <?php
              require_once("../templates/laf/publicLostReports.php"); // Do not rearrange these require_once, publicLostReports.php won't be included and I don't know why
              if ($loggedIn) {
                require_once("../templates/laf/submitFoundItem.php");
                require_once("../templates/laf/searchItems.php");
                require_once("../templates/laf/searchReports.php");
              }
            ?>
            <div class='feedback'></div>
          </div>
        </div>
    <?php if (!$loggedIn) {?>
      </div>
    </div>
    <?php }?>
  </body>
  <?php
    if (!$loggedIn) {
      require_once("../templates/footer.php");
    }
  ?>
</html>

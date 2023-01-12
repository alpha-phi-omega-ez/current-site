<?php
include_once("/var/www/templates/functions/session_functions.php");
startSession();
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Join APO | Alpha Phi Omega - Epsilon Zeta Chapter</title>
  </head>

  <?php require_once("/var/www/templates/navbar.php")?>
  <body>   
    <div id="main">
      <div class="col">
        <h1>Rush Events</h1>
        <img id="rush_calendar_img" src="/images/APO_Rush_F2022.png"/>
      </div>
    </div>
  </body>
  <?php require_once("/var/www/templates/footer.php")?>
</html>
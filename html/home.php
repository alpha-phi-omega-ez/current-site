<?php
include_once("/var/www/templates/functions/session_functions.php");

startSession();
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Home | Alpha Phi Omega - Epsilon Zeta Chapter</title>
  </head>

  <?php require_once("../templates/navbar.php")?>
  <body>
    <img id="homepageImg" src="images/APO_Membership_Photo_2022.jpg">
  </body>
  <?php require_once("../templates/footer.php")?>
</html>

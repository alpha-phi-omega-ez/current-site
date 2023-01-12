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
        <h1>Bylaws</h1>
        <iframe width="100%" height="960px" src="/files/Chapter_Bylaws.pdf"></iframe>
      </div>
    </div>
  </body>
  <?php require_once("/var/www/templates/footer.php")?>
</html>
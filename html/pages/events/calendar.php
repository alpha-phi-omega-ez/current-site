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
        <h1>Calendar</h1>
        <iframe frameborder="0" height="600" scrolling="no" src="https://calendar.google.com/calendar/embed?src=c_62lfl104jf8npqgaqm9ga72opc%40group.calendar.google.com&ctz=America%2FNew_York" style="border: 0" width="100%" max-width="800px"></iframe>
      </div>
    </div>
  </body>
  <?php require_once("/var/www/templates/footer.php")?>
</html>

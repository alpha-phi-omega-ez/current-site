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
        <h1>BMOC</h1>
        <p>&#x1F171;MoC (&#x1F171;iggest Meme on Campus), formerly known as MMoC (Meanest Man on Campus), is a charity competition hosted by the Alpha Phi Omega chapter here at RPI. The way the competition works is simple: candidates campaign for who is the biggest meme, and whoever receives the most votes wins. 80% of the money raised throughout the term of the competition is then donated to the biggest memes charity of choice (selected at the beginning of the campaign). The remaining 20% of the money raised each week will go to that week's leading candidate, meaning that up to 6 charities will benefit from the competition this year.</p>
      </div>
    </div>
  </body>
  <?php require_once("/var/www/templates/footer.php")?>
</html>
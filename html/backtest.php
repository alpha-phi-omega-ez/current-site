<?php
include_once("/var/www/templates/functions/session_functions.php");
startSession();
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Backtests | Alpha Phi Omega - Epsilon Zeta Chapter</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="css/backtests.css">
    <script src="scripts/searchClasses.js"></script>
    <?php
    if (checkLoginStatus()) {
      echo '<script src="scripts/manageTests.js"></script>';
    }?>
  </head>

  <?php require_once("../templates/navbar.php")?>
  <body>
    <div id="main">
      <div class="col">
        <h1>Back Tests</h1>
        <p>Studying for an exam? Use our back test files! We have drawers full of tests for many different subjects taught here at RPI. You may view our online catalog below.</p>
        <p>All of our tests have been donated by RPI students. Names are always crossed out to ensure privacy. Please donate your old tests by dropping them off at our office (Union 3420), 
	putting them in the folder on our bulletin board outside the office, or by emailing us at <a href="mailto:office@apoez.org">office@apoez.org</a>. All donations are greatly appreciated!</p>
        <h3>Catalog</h3>
      </div>
    <?php if (checkLoginStatus()) {
      require_once("../templates/backtest/manageBacktests.php");
    }
    require_once("../templates/backtest/searchClasses.php");
    ?>
    </div>
  </body>
  <?php require_once("../templates/footer.php")?>
</html>


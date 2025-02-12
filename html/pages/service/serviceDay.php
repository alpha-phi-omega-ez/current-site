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
	<h1>Service Day</h1>
        <p>Service Day will be hosted by APO on Saturday October 5th to connect RPI students 
	with organizations in the community to help make a difference. This will be our 
	5th annual RPI Service Day, we have events planned at the 
	Regional Food Bank, Frear Park, Northern Rivers, Sanctuary for Independent Media, Mohawk Hudson Humane Society, and Capital Roots
	and card-making on campus for soldiers. Food and transportation will be 
	provided to all participants. Sign up below! </p>
	<iframe src="https://docs.google.com/forms/d/e/1FAIpQLSe82cFEV5VeRs7-VT1x_Gz3cs6w0eGsKG_be3K4gumNQKniUQ/viewform?embedded=true" width="640" height="4669" frameborder="0" marginheight="0" marginwidth="0">Loading¿</iframe>
      </div>
    </div>
  </body>
  <?php require_once("/var/www/templates/footer.php")?>
</html>

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
        <p>Service Day will be hosted by APO on October 1st to connect RPI students 
	with organizations in the community to help make a difference. This will be our 
	third Service Day and first after the pandemic, we are planning to do service at the 
	Regional Food Bank, Frear Park, Oakwood Community Center, Sanctuary for Independent Media, Troy Bike Rescue, 
	and a campus clean on campus and at other nearby parks. Food and transportation will be 
	provided to all participants. Sign up below and view our poster below the sign up. </p>
	<iframe src="https://docs.google.com/forms/d/e/1FAIpQLSewZKQlw7weGQ6mXMzGCZaH2pJntHFjPU73WbtlirIuqQToTA/viewform?embedded=true" width="640" height="2800" frameborder="0" marginheight="0" marginwidth="0">Loading…</iframe>
      </div>
    </div>
  </body>
  <?php require_once("/var/www/templates/footer.php")?>
</html>

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
	<h1>Alumni</h1>
        <p>
          <b>About the EZAA</b>
          <br>
          The <a href="http://www.apoezaa.org/">Epsilon Zeta Alumni Association</a> is an organization focused on keeping the alumni of the Epsilon Zeta chapter of Alpha Phi Omega informed about the chapter's goings on, events, and ways to stay active by participating in service events. It is also a great way for EZ alumni to keep in touch.
        </p>
        <p>
          <b>Alumni Listserv</b>
          <br>
          If you are interested in signing up for Epsilon Zeta's alumni listserv, please contact us&nbsp;by emailing&nbsp;<a href="mailto:apo@union.lists.rpi.edu" onmouseout="window.status='';" onmouseover="window.status='General E-Mail';return true;" title="General E-Mail">apo (at) union (dot) lists (dot) rpi (dot) edu</a>. We only send out e-mails regarding large service projects and ceremonies over that listserv, so you don't need to worry about getting any spam from us.
        </p>
      </div>
    </div>
  </body>
  <?php require_once("/var/www/templates/footer.php")?>
</html>
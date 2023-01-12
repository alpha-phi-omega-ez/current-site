<?php
include_once("/var/www/templates/functions/session_functions.php");
include_once("/var/www/templates/functions/validate_info.php");
checkLoginStatus();
?>
<html>
  <head>
    <title>Log In | Alpha Phi Omega - Epsilon Zeta Chapter</title>
    <link rel="stylesheet" href="/css/login.css">
    <style>
      .updateBanner {
        display: flex;
        border: 2px solid red;
        border-radius: 14px;
        height: 60px;
        align-content: center;
        justify-content: center;
        flex-direction: column;
        padding-left: 80px;
        padding-right: 80px;
        text-align: center;
        background: #f7b5b5;
        margin-top: 20px;
      }
      .child {
  display: flex;
  align-items: center;
  justify-content: center;
  height: 40px;
  flex-direction: column;
  padding: 10px;
  padding-left: 80px;
  padding-right: 80px;
  border: 2px solid gray;
  border-radius: 14px;
  transition: 0.5s;      }


      .updateBanner p {
        margin: 0px;
      }
    </style>
  </head>
  <script src="https://accounts.google.com/gsi/client" async defer></script>
  <?php include_once("../templates/navbar.php")?>
  <body>
    <div id="main">
      <div class="col">
        <div class="updateBanner">
          <p>Student accounts and verification has been removed as of 9/7/2021. Brothers, please use your Google Login.</p>
        </div>
        <?php
	  require_once 'vendor/autoload.php';

	  error_reporting(E_ALL);
	  ini_set('display_errors', 1);

	  $CLIENT_ID = "54137757791-2d5e2d0kcvomq1pki9mjut8ctlg3r9th.apps.googleusercontent.com";

	  $OAUTH2_CLIENT_SECRET = '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';

	  if (isset( $_POST['credential'] ) ){
  	    $id_token = $_POST['credential'];
	    $client = new Google_Client();  // Specify the CLIENT_ID of the app that accesses the backend
	    $client->setClientId($CLIENT_ID);
	    $client->setClientSecret($OAUTH2_CLIENT_SECRET);
	    $payload = $client->verifyIdToken($id_token);

	    if ($payload) {
	      $userid = $payload['sub'];
	      $domain = $payload['hd'];
	      if ( $domain == 'apoez.org'){
	        startSession();
     		session_regenerate_id();
   		$_SESSION['loggedIn'] = True;
		header("Location: home.php");
		die();

	      }
	    }
	  } else {

	  }
?>

  <div class="child">
        <div id="g_id_onload"
     	  data-client_id="54137757791-2d5e2d0kcvomq1pki9mjut8ctlg3r9th.apps.googleusercontent.com"
     	  data-context="signin"
     	  data-ux_mode="redirect"
     	  data-login_uri="https://www.apoez.org/glogin.php"
     	  data-auto_prompt="false">
	</div>

	<div class="g_id_signin"
     	  data-type="standard"
     	  data-shape="rectangular"
     	  data-theme="outline"
     	  data-text="signin_with"
     	  data-size="large"
     	  data-logo_alignment="center">
	</div> 
</div>


      </div>
    </div>
  </body>
  <?php require_once("../templates/footer.php")?>
<html>

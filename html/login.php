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

      .updateBanner p {
        margin: 0px;
      }
    </style>
  </head>

  <?php include_once("../templates/navbar.php")?>
  <body>
    <div id="main">
      <div class="col">
        <div class="updateBanner">
          <p>Student accounts and verification has been removed as of 9/7/2021.</p>
        </div>
        <div class='loginForm'>
          <div class='login'>
            <form action="verify_user.php", method="post">
              <label for="user">Username</label>
              <input type="text" id="user" name="user"></input><br>
              <label for="pass">Password</label>
              <input type="password" id="pass" name="pass"></input><br>
              <input id="loginSubmit" type="submit" value="Log In">
            </form>
          </div>
        </div>
        <div id="newAcc">
          <a href="createAccount.php">Create an Account</a>
        </div>
        <?php 
          if (isset($_SESSION['loginError'])) { // Check if a login attempt was made
            if ($_SESSION['loginError'] === True) {
              echo "<div class='loginError'><p>Incorrect Username or Password</p></div>";
              $_SESSION['loginError'] = False;
            }
          }
          if (isset($_SESSION['create']) && validate_is_set($_SESSION['create'])) { // Check if a new account was made and alert how to activate the account
            if (validate_string_regex($_SESSION['create'], "/^[0-9A-Za-z]{12}$/")) {
              echo "<div class='accountReady'>Account successfully created. Send the token '" . $_SESSION['create'] . "' to your system administrator so they can activate your account</div>";
              unset($_SESSION['create']);
            } else {
              header("Location: createAccount.php");
              die();
            }
          }
        ?>
      </div>
    </div>
  </body>
  <?php require_once("../templates/footer.php")?>
<html>

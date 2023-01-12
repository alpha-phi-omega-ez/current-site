<?php
include_once("/var/www/templates/functions/session_functions.php");
include_once("/var/www/templates/functions/validate_info.php");
startSession();
if (checkLoginStatus()) {
  header("Location: home.php");
  die();
}
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Join APO | Alpha Phi Omega - Epsilon Zeta Chapter</title>
    <link rel="stylesheet" href="/css/createAccount.css">
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

  <?php require_once("/var/www/templates/navbar.php")?>
  <body>
    <div id="main">
      <div class="col">
        <div class='updateBanner'>
          <p>Student accounts and verification has been removed as of 9/7/2021.</p>
          <p>Accounts are now for Brothers of Alpha Phi Omega, Epsilon Zeta Chapter only.</p>
        </div>
        <div class='accountForm'>
          <form id="newAccount" autocomplete="False" action="makeNewAccount.php" method="post">
            <div class='accountDiv'>
              <label for="newUser">Username</label>
              <input type="text" id="newUser" name="newUser">
            </div>
            <div class='accountDiv'>
              <label for="newPass">Password</label>
              <input type="password" id="newPass" name="newPass">
            </div>
            <div class='accountDiv'>
              <label for="newPassVer">Confirm Password</label>
              <input type="password" id="newPassVer" name="newPassVer">
            </div>
            <div class='accountDiv'>
              <input type="submit" value="Create Account" id="createNewUser">
            </div>
          </form>
        </div>
        <?php
        if (isset($_SESSION['create']) && validate_is_set($_SESSION['create'])) { // Check and see if an error was made creating the account
          if (validate_string_regex($_SESSION['create'], "/^[A-Za-z0-9]{12}$/")) {
            header("Location: login.php");
            die();
          } else {
            echo "<div class='accountError'>";
            switch ($_SESSION['create']) {
              case "nameError":
                echo "Usernames can only contain letters and numbers and must be at least 5 characters long";
                break;
              case "passError":
                echo "Passwords may only contain letters, numbers, and these special characters: !@#$%^&*()?<p>Passwords must contain at least 8 characters, a number, a capital and lowercase letter, and a special character.";
                break;
              case "matchError":
                echo "The passwords do not match";
                break;
              case "existsError":
                echo "That username is already taken";
                break;
            }
            echo "</div>";
            unset($_SESSION['create']);
          }
        }
        ?>
      </div>
    </div>
  </body>
  <?php require_once("/var/www/templates/footer.php")?>
</html>
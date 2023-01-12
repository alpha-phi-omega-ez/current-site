<?php
include_once("/var/www/templates/functions/random_functions.php");
include_once("/var/www/templates/functions/validate_info.php");
include_once("/var/www/templates/functions/database_functions.php");
include_once("/var/www/templates/functions/session_functions.php");

startSession();

//Attempt to connect to MariaDB
if (!($conn = connectToDatabase("login_database"))) {
  header("Location: errorPage.php");
  die();
}

//validate inputs
if (validate_string_regex($_POST["newUser"], "/^[A-Za-z0-9]{5,16}$/")) {
  $user = $_POST['newUser'];
} else {
  $_SESSION['create'] = "nameError";
  header("Location: createAccount.php");
  die();
}
if (validate_string_regex($_POST['newPass'], "/^(?=[!@#$%^&*()?A-Za-z0-9]*[0-9])(?=[!@#$%^&*()?A-Za-z0-9]*[a-z])(?=[!@#$%^&*()?A-Za-z0-9]*[A-Z])(?=[!@#$%^&*()?A-Za-z0-9]*[!@#$%^&*()?])[!@#$%^&*()?A-Za-z0-9]{8,50}$/")) {
  $pass = $_POST['newPass'];
} else {
  $_SESSION['create'] = "passError";
  header("Location: createAccount.php");
  die();
}
if (validate_string_regex($_POST['newPassVer'], "/^(?=[!@#$%^&*()?A-Za-z0-9]*[0-9])(?=[!@#$%^&*()?A-Za-z0-9]*[a-z])(?=[!@#$%^&*()?A-Za-z0-9]*[A-Z])(?=[!@#$%^&*()?A-Za-z0-9]*[!@#$%^&*()?])[!@#$%^&*()?A-Za-z0-9]{8,50}$/")) {
  $passVer = $_POST['newPassVer'];
} else {
  $_SESSION['create'] = "matchError";
  header("Location: createAccount.php");
  die();
}

if ($pass != $passVer) { // Ensure passwords match
  $_SESSION['create'] = "matchError";
  header("Location: createAccount.php");
  die();
} else {
  $params = array($user, $user);
  $paramTypes = "ss";
}

// Find out if the username is already taken
$find = "SELECT userId FROM users u WHERE username=? UNION SELECT userId FROM newUsers nu WHERE username=?";
if (!($stmt = query($find, $conn, $paramTypes, $params))) {
  header("Location: errorPage.php");
  die();
} else {
  $results = $stmt->get_result();
}

if ($results->num_rows > 0) {
  $_SESSION['create'] = "existsError";
  header("Location: createAccount.php");
  die();
} else {
  $newId = random_str(12);
  $pass = password_hash($pass, PASSWORD_BCRYPT);
  $insert = "INSERT INTO newUsers (userId, username, password) VALUES (?, ?, ?)";
  $params = array($newId, $user, $pass);
  $paramTypes = "sss";
  if (!(query($insert, $conn, $paramTypes, $params))) {
    header("Location: errorPage.php");
    die();
  } else {
    $_SESSION['create'] = $newId;
    header("Location: login.php");
    die();
  }
}

?>

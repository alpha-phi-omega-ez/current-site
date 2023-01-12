<?php
/*
This file contains various functions used to manage sessions
*/

function redirectToHome() {
  header("Location: home.php");
  logout();
  return False;
}

function startSession() {
  // If there isn't an active session, start one
  if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
    if (!isset($_SESSION['loggedIn'])) {
      $_SESSION['loggedIn'] = False;
    }
  }
  request();
}

function checkLoginStatus($onFalse="") {
  // Check if the user is logged in and that they haven't been idle for too long
  startSession();
  if (!request()) {
    if ($onFalse != "") {
      call_user_func($onFalse);
    }
    return False;
  }
  if (isset($_SESSION['loggedIn'])) {
    if ($_SESSION['loggedIn'] == True) {
      return True;
    } else {
      if ($onFalse != "") {
        call_user_func($onFalse);
      }
      return False;
    }
  } else {
    if ($onFalse != "") {
      call_user_func($onFalse);
    }
    return False;
  }
}

function logout() {
  // Start a new session, destroying the current one. Set the session to logged out, and set the last request time to now
  session_regenerate_id(True);
  $_SESSION['loggedIn'] = False;
  $_SESSION['lastRequest'] = time();
  return True;
}

function request() {
  // Checks when the last request occured, ensure it was reasonable, and set the new last request time
  $maxRequests = 10; // Maximum number of requests per second
  if (session_status() !== PHP_SESSION_ACTIVE) {
    startSession();
  }
  if (!isset($_SESSION['lastRequest'])) { // Last request time not found
    logout();
    return False;
  }
  if (time() > $_SESSION['lastRequest'] + (120 * 60)) { // Last request was too long ago (120 minutes, 60 seconds per minute)
    logout();
    return False;
  }
  /*if (time() == $_SESSION['lastRequest']) { // If multiple requests are made in the same second
    if (isset($_SESSION['recentRequests'])) { // Check if this is the first time this happened
      if (end($_SESSION['recentRequests']) == time()) { // Check if list of recent requests also occurred this second
        $_SESSION['recentRequests'][] = time();
        if (count($_SESSION['recentRequests']) > $maxRequests - 1) { // If too many requests were made
          logout();
          return False;
        }
      } else { // Previous list of recent requests didn't occur this second
        $_SESSION['recentRequests'] = array();
        $_SESSION['recentRequests'][] = time();
      }
    } else { // If this is the first occurance
      $_SESSION['recentRequests'] = array();
      $_SESSION['recentRequests'][] = time();
    }
  } else { // If this request if the first to occur this second
    if (isset($_SESSION['recentRequests'])) {
      unset($_SESSION['recentRequests']);
    }
  } // Bad implementation, being commented out to use apache's max requests config option, left in case something similar is needed*/
  $_SESSION['lastRequest'] = time();
  return True;
}
?>

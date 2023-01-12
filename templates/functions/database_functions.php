<?php
/*
This file contains various functions for interacting with the mysql databases.
*/


function connectToDatabase(string $database) {
  // Connects to a user defined database and returns either a \mysqli object or false
  $creds = get_credentials($database);

  $dbhost = "localhost:3306";
  $dbuser = $creds["user"];
  $dbpass = $creds["pass"];
  $dbname = $creds["name"];
  $conn = new mysqli($dbhost, $dbuser, $dbpass, $dbname);

  if ($conn->connect_error) {
    return false;
  } else {
    return $conn;
  }
}

function query(string $query, \mysqli $conn, string $paramTypes="", array $params=[], $onError="") {
  // Prepares, binds parameters to, and executes a query, and returns either the executed statement or false
  if (!($stmt = $conn->prepare($query))) {
    if ($onError != "") {
      call_user_func($onError);
    }
    return false;
  }
  if ($paramTypes != "") {
    if (!$stmt->bind_param($paramTypes, ...$params)) {
      if ($onError != "") {
        call_user_func($onError);
      }
      return false;
    }
  }
  if (!$stmt->execute()) {
    if ($onError != "") {
      call_user_func($onError);
    }
    return false;
  }

  return $stmt;
}

function get_last_id (\mysqli $conn) {
  // Gets the auto_incremented ID of the last row inserted into the database
  if (!($stmt = query("SELECT LAST_INSTERTED_ID()", $conn))) {
    return false;
  }
  $results = $stmt->get_result();
  $row = $results->fetch_array(MYSQLI_NUM);
  return $row[0];
}

function get_credentials(string $section) {
  // Reads the credentials from the site.ini file
  $ini = parse_ini_file("/var/site.ini", true);
  return $ini[$section];
}
?>
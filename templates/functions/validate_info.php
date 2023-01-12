<?php
/*
This file contains functions for validating user input of various types in various ways
*/


function validate_string_regex($input, string $regexMatch) {
  // Validates an input string, ensuring it's set, not empty, and matches a regular expression
  if (!isset($input) || empty($input)) {
    return false;
  } elseif (!preg_match($regexMatch, $input)) {
    return false;
  } else {
    return true;
  }
}

function validate_is_set($input) {
  // Validates that an input is set and not empty
  if (!isset($input) || empty($input)) {
    return false;
  } else {
    return true;
  }
}

function validate_date($date, $format="Y-m-d") {
  // Validates that a given date is set, not empty, and matches the given format
  if (!isset($date) || empty($date)) {
    return false;
  }
  if (date($format, strtotime($date)) == $date) {
    return true;
  } else {
    return false;
  }
}

?>
<?php
/*
This file contains various functions used to generate random structures
*/


function random_str(
  int $length = 8,
  string $keyspace = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"
): string {
  // Returns a string of length $length of random characters chosen from $keyspace
  if ($length < 1) {
    throw new \RangeException("Length must be a positive integer");
  }
  $pieces = [];
  $max = strlen($keyspace) - 1;
  for ($i = 0; $i < $length; $i++) {
    $pieces []= $keyspace[random_int(0, $max)];
  }
  return implode('', $pieces);
}

?>
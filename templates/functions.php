<?php
/*
This file contains include statements for every function in the /var/www/templates/functions folder
It can be included in any file to have access to every function, or each include statement can be
individually used in the file. A short description of each file is commented after the include statement.
*/

include_once("/var/www/templates/functions/database_functions.php");
// Contains functions for interacting with the databases

include_once("/var/www/templates/functions/random_functions.php");
// Contains custom functions for generating various random things

include_once("/var/www/templates/functions/validate_info.php");
// Contains functions for validating user input

include_once("/var/www/templates/functions/text_parsing_functions.php");
// Contains functions and constants for parsing text

include_once("/var/www/templates/functions/session_functions.php");
// Contains functions and constants for managing sessions

?>
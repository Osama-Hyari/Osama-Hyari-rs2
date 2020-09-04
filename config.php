<?php
/* Database credentials. Assuming you are running MySQL
 server with default setting (user 'root' with no password) */
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', 'root');
define('DB_NAME', 'rs2');

// $link = mysql_connect("$hostname", "$username", "$password");

/* Attempt to connect to MySQL database */
$mysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// print_r($mysqli);
// Check connection
if($mysqli === false){
    // echo "<script>console.log('Debug Objects: " . string($mysqli) . "' );</script>"      ;  // echo mysql_error();
    print_r($mysqli);

    die("ERROR: Could not connect. " . $mysqli->connect_error);
}

// echo "Connected susccessfully";
?>
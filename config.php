<?php
/* Database credentials. Assuming you are running MySQL
server with default setting (user 'root' with no password) */
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'melon');
define('DB_PASSWORD', 'Hello123*');
define('DB_NAME', 'mb_register');
 
/* Attempt to connect to MySQL database */
$conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
 
// Check connection
if(!$conn){
    echo"ERROR: Could not connect. " . mysqli_connect_error();
}
?>


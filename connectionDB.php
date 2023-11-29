<?php
// assigning variables to the server connection
$servername = "localhost";
$username = "u-210061822";
$password = "lEQOxiR7x2vMo23";
$dbname = "u_210061822_db";

//database connetion sored as a variable so that it can be referenced across different pages
$conn = new mysqli($servername, $username, $password, $dbname);

//chcking the connection with a simple if statement
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
<?php
// assigning variables to the server connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "acegear";

//database connetion sored as a variable so that it can be referenced across different pages
$conn = new mysqli($servername, $username, $password, $dbname);

//chcking the connection with a simple if statement
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
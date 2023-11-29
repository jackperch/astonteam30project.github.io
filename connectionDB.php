<?php
// assigning variables to the server connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "acegear";

try {
	$db = new PDO("mysql:dbname=$db_name;host=$db_host", $username, $password); 
	#$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $ex) {
	echo("Failed to connect to the database.<br>");
	echo($ex->getMessage());
	exit;
}
?>
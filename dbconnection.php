<?php
$host="localhost"; // Host name 
$username="yourusername"; // Mysql username 
$password="yourpassword"; // Mysql password 
$db_name="yourdbname"; // Database name 


// Connect to server and select databse.
$connection =mysqli_connect($host, $username, $password,$db_name)or die("cannot connect");

//if (mysqli_connect_errno())
//	echo "Database connection failed";
//else	
//	echo "Database connection success";
?>



<?php
require_once('../phplogin.php');

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

	//This file takes all current check-ins and outputs them as a JSON formatted list
	$sql = mysqli_query($conn,"SELECT * FROM geolocate");
	$total = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) as total FROM geolocate"));
	$start = 0;
	echo "[";
	foreach ($sql as $user) {
    echo "{\"Title\":\"$user[Username]\",
		 \"latitude\":\"$user[Lat]\",
    	 \"longitude\":\"$user[Log]\",
	 \"Image\":\"$user[Image]\",
	 \"Time\":\"$user[Time]\"";
	echo '}';
		if(($start+1)<$total['total']){
			echo ",";
		}
	$start = $start + 1;
	}
	echo "]";







$conn->close();
?>

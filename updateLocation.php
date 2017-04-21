<?php
require_once('../phplogin.php');


//Redirect
echo '<meta http-equiv="refresh" content="0; url=./" />';

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

if(isset($_POST['submit'])){
	echo "TEST";
	$username 	= $_POST['Username'];
	$fullname 	= $_POST['FullName'];
	$lat 		= $_POST['Lat'];
	$long 		= $_POST['Long'];
	$imageURL 	= $_POST['ImageURL'];

	//echo $username;
	//echo $fullname;
	//echo $lat;
	//echo $long;
	//echo $imageURL;
	//echo "INSERT INTO geolocate (Username, FullName, Lat, Log, Image) VALUES  ('$username','$fullname','$lat','$long','$imageURL')";

	if($username!='NULL'){
		mysqli_query($conn,"DELETE FROM geolocate WHERE Username='$username'");
		mysqli_query($conn,"INSERT INTO geolocate (Username, FullName, Lat, Log, Image) VALUES  ('$username','$fullname','$lat','$long','$imageURL')");
	}
}




$conn->close();
?>

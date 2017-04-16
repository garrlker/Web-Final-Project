<?php
//Garrett Walker
//Table Assignment 1
require_once('../phplogin.php');

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

$sql = "SELECT id, firstname, lastname, image, note FROM geolocate";
$result = $conn->query($sql);

//HTML
readfile("./geolocate.html");




/*if ($result->num_rows > 0) {
    echo '<table border=\"1\"><tr><th>Command</th><th>ID</th><th>Name</th><th>Image</th><th>Note</th></tr>';
    // output data of each row
    while($row = $result->fetch_assoc()) {
	echo "<tr><td><button onclick=\"window.location='delete.php?id=".$row["id"]."'\">Delete</button></td><br>";
	echo "<td>". $row["id"]. "</td><td>" . $row["firstname"]. " " . $row["lastname"]. "</td>";
	echo "<td><img src=\"".$row["image"]."\" height=\"128\" width=\"128\"> </td><td>".$row["note"]."</td></tr>";
    }
} else {
    echo "0 results";
}
echo "</html>";*/
$conn->close();
?>

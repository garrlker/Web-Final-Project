<?php
require_once('../phplogin.php');
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Geolocation</title>
    <!-- Meta Tags-->
	<meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <meta name="google-signin-scope" content="profile email">
    <meta name="google-signin-client_id" content="733531887668-ba55v3a0u7cnjdljh8urp0long9a8noo.apps.googleusercontent.com">
    
    <!-- Latest compiled and minified CSS BOOTSTRAP-->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
	<script src="https://apis.google.com/js/platform.js" async defer></script>

	<!-- Style -->
	<style>
	/* Always set the map height explicitly to define the size of the div element that contains the map. */
	#map {
		float: left;
		height: 50%;
		width:  50%;
	}
	/* Optional: Makes the sample page fill the window. */
	html, body {
		height: 100%;
		margin: 0;
		padding: 0;
	}
	* { 
		margin: 0; 
		padding: 0; 
		box-sizing: border-box; 
	}
	body { 
		font: 13px Helvetica, Arial; 
	}
	form.Chat { 
		
		background: #0C030F;
		border-radius: 10px;
		border: 1px solid #000000;
		margin: 5px;
		float: right;
		width:600px;
	}
	form input.Chat { 
		border: 0; 
		padding: 10px;
		float: right;
		width:800px;
	} 
	button.Chat { 
		width: 9%; 
		background: rgb(130, 224, 255); 
		border: none; 
		padding: 10px; 
	}
	nav ul{height:200px; width:100%;}
	nav ul{overflow:hidden; overflow-y:scroll;}
	#messages { 
		list-style-type: none; 
		margin: 0; 
		padding: 0; 
	}
	#messages li { 
		padding: 5px 10px; 
	}
	#messages li:nth-child(odd) { 
		background: #eee; 
	}
	#messages { 
		margin-bottom: 40px; 
	}
	ul {
		
    		border: 4px solid gray;
    		border-radius: 9px;
	}

    </style>

  </head>
  	<body>
    	<!-- Signin button, then map, then chat-->
    	<div class="g-signin2" data-onsuccess="onSignIn" data-theme="dark"></div>
    	<div id="map"></div> 
	<nav>
    	<ul id="messages"></ul>
	</nav>
	<form name="ChatForm" class="Chat" action="">
     		<input id="m" autocomplete="off" />
		<button type="submit" class="Chat">Send</button>
    	</form>


		<form action="/~garbwalk/geolocation/Web-Final-Project/updateLocation.php" method="post">
			<input type="hidden" id="Userfield" name="Username" value="NULL">
			<input type="hidden" id="Namefield" name="FullName" value="NULL">
			<input type="hidden" id="Latfield" name="Lat" value="0">
			<input type="hidden" id="Longfield" name="Long" value="0">
			<input type="hidden" id="Timefield" name="Time" value="0">
			<input type="hidden" id="Imagefield" name="ImageURL" value="NULL">
			<input type="submit" name="submit" value="Check In">
		</form>

 		<?php
		$sql = mysqli_query($conn,"SELECT * FROM geolocate");
		echo "<table border=\"1\"><tr><th>Username</th><th>Lat</th><th>Long</th><th>Image URL</th><th>Time Stamp</th></tr>";
		foreach ($sql as $user) {
        		echo "<tr><td><a onclick=\"mapHandle.panTo(new google.maps.LatLng(parseInt($user[Lat]),parseInt($user[Log])))\" href=\"javascript:void(0);\">$user[Username]</a></td>
                		<td>$user[Lat]</td>
                		<td>$user[Log]</td>
                		<td><img src=\"$user[Image]\"></td>
                		<td>$user[Time]</td></tr>";
		}

		echo "</table>";
 		$conn->close();
		?>

		
		<script src="https://cdn.socket.io/socket.io-1.2.0.js"></script>
		<script src="https://code.jquery.com/jquery-1.11.1.js"></script>
    	<script>
    	//SocketIO ala Chat
		//On submit, append text field to FullName hidden field and send
		$(function () {
			var socket = io("https://secret-chamber-75151.herokuapp.com/");
			$('form[name="ChatForm"]').submit(function(){
			socket.emit('chat message', $('#Namefield').val() + ": " + $('#m').val());
			$('#m').val('');
			return false;
		});
		//When we recieve a message, parse if link and output
		    socket.on('chat message', function(msg){ //msg.user
			notifyMe($('#Namefield').val(), msg.comment);
			var message = msg;
			//console.log(message);
			if (message.indexOf('http://')>=0 || message.indexOf('https://')>=0){
				message = '<li><a href=\"' + message.split(" ")[2] + '\">' + message +'</a>';
			}else{
				message = '<li>' + message;
			}

			$('#messages').append(message);
			messages.scrollTop = messages.scrollHeight;
			document.documentElement.scrollTop = document.body.scrollTop = 10;
			//$('#messages').append($('<li>').text(msg));
			});
		});

		//If http, redirect to https
		var loc = window.location.href+'';
		if (loc.indexOf('http://')==0){
			window.location.href = loc.replace('http://','https://');
		}


		//notifiyng user of chat
		function notifyMe(user,message) {
		// Let's check if the browser supports notifications
	    if (!("Notification" in window)) {
		  alert("This browser does not support desktop notification");
	    }
	  // Let's check if the user is okay to get some notification
	  else if (Notification.permission === "granted") {
		// If it's okay let's create a notification
	  var options = {
			body: message,
			dir : "ltr"
		};
	  var notification = new Notification(user + " Posted a comment",options);
	  }
	  // Otherwise, we need to ask the user for permission
	  else if (Notification.permission !== 'denied') {
		Notification.requestPermission(function (permission) {
		  // Whatever the user answers, we make sure we store the information
		  if (!('permission' in Notification)) {
			Notification.permission = permission;
		  }
		  // If the user is okay, let's create a notification
		  if (permission === "granted") {
			var options = {
					body: message,
					dir : "ltr"
			};
			var notification = new Notification(user + " Posted a comment",options);
		  }
		});
	  }
	}

		//On Google Account Sign In
		function onSignIn(googleUser) {
			// Useful data for your client-side scripts:
			var profile = googleUser.getBasicProfile();
			document.getElementById("Namefield").value = profile.getName();
			document.getElementById("Imagefield").value = profile.getImageUrl();
			document.getElementById("Userfield").value = profile.getEmail();
			// The ID token you need to pass to your backend:
			var id_token = googleUser.getAuthResponse().id_token;
			console.log("ID Token: " + id_token);
		};

		// Note: This example requires that you consent to location sharing when
		// prompted by your browser. If you see the error "The Geolocation service
		// failed.", it means you probably did not give permission for the browser to
		// locate you.
		var mapHandle; //Globals are bad, but i dont care
		function initMap() {
			var map = new google.maps.Map(document.getElementById('map'), {
				//Martin's GPS Coordinates
				center: {
					lat: 36.339637, 
					lng: -88.851132
				},
				zoom: 6
			});

			var infoWindow = new google.maps.InfoWindow({map: map});
			mapHandle = map;
			// Try HTML5 geolocation.
			if (navigator.geolocation) {
				navigator.geolocation.getCurrentPosition(function(position) {
				var pos = {
					lat: position.coords.latitude,
					lng: position.coords.longitude
				};
				//Set our lat/Long hidden fields to geolocates coordinates, then
				//Set our map to those coordinates. It only does that when a position is fetched
				document.getElementById("Latfield").value = position.coords.latitude;
				document.getElementById("Longfield").value = position.coords.longitude;
				infoWindow.setPosition(pos);
				infoWindow.setContent('Location found.');
				map.setCenter(pos);
				},
				function(){//This is where javascript sucks, this is called a callback function
					handleLocationError(true, infoWindow, map.getCenter());
				});
			}else{
				// Browser doesn't support Geolocation
				handleLocationError(false, infoWindow, map.getCenter());
	        }
			var xmlhttp = new XMLHttpRequest();
	        xmlhttp.onreadystatechange = function() {
				if (this.readyState == 4 && this.status == 200) {
					myObj = JSON.parse(this.responseText);
					populateMarkers(myObj);
				}
			};
	        xmlhttp.open("GET", "locationMarkers.php", true);
	        xmlhttp.send();
	     
	     	//After our XMLHTTP request above get's our JSON and parses it into an objet, add it to the map
			function populateMarkers(myObj){
				var markers=[];//I assume this is a dynamic array
				var icons=[];
				for (var key in myObj) {
					var myLatLng = new google.maps.LatLng(myObj[key].latitude,myObj[key].longitude);
					//Actual marker object
					icons[key] = {
						url: myObj[key].Image,
						scaledSize: new google.maps.Size(48, 48) // scaled size
						//origin: new google.maps.Point(48,48) // origin
					};
					markers[key] = new google.maps.Marker({
						position: myLatLng,
						map: map,
						title: myObj[key].Title +" - "+ myObj[key].Time,
						icon: icons[key]
						//icon: myObj[key].Image,

					});
				//DEBUG
				console.log(markers);
				console.log(myLatLng.lat);
				console.log(myLatLng.lng);
				console.log(myObj[key].Title);
				console.log("Added");
				}
			}


			function handleLocationError(browserHasGeolocation, infoWindow, pos) {
				infoWindow.setPosition(pos);
				infoWindow.setContent(browserHasGeolocation ?
                              'Error: The Geolocation service failed.' :
                              'Error: Your browser doesn\'t support geolocation.');
			}
		}


    	</script>
    <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA7knGxQgHyP4XHGFw3VmivYNzLTxN3LXY&callback=initMap">
    </script>
  </body>
</html>

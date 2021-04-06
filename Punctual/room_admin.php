<?php

// Function for alert box pop up
function phpAlert($msg) {
	echo '<script>alert("' . $msg . '")</script>';
}
// Start session 
session_start();
 
$qrurl = "http://api.qrserver.com/v1/create-qr-code/?data=http://ec2-54-173-102-15.compute-1.amazonaws.com/Punctual/Punctual/qrjoin.php?roomno=". $_SESSION["adminRoomID"];
// Checks if user is logged in, if they aren't then redirect
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

// Linking config file
require_once "config.php";

// When form is submitted, do this
if($_SERVER["REQUEST_METHOD"] == "POST"){
            $roomIDnum = $_SESSION["adminRoomID"];
			$sql = "DELETE FROM rooms WHERE roomid = ?";
         
			if($stmt = mysqli_prepare($connection, $sql)) {
				mysqli_stmt_bind_param($stmt, "s", $roomIDnum);
			
				if(mysqli_stmt_execute($stmt)) {
					phpAlert("Room Deleted!");
				}
				else {
					phpAlert("Something bad happened :( Try refreshing the page");
				}

				mysqli_stmt_close($stmt);
			}
			$_SESSION["adminRoomID"] = null;
			header("location: main_admin.php");
}
?>

<script>

	function CopyImageById(Id) 
	{
		var imgs = document.createElement('img');
		imgs.src = document.getElementById(Id).src;
		
		//alert ('Create image') ;

		var bodys = document.body ;
		bodys.appendChild(imgs);
		//alert ('Image on document')
		
		
		if (document.createRange)  
		{
			//alert ('CreateRange work');
			var myrange = document.createRange();
			myrange.setStartBefore(imgs);
			myrange.setEndAfter(imgs);
			myrange.selectNode(imgs);

		}
		else
		{
			alert ('CreateRange NOT work');
		}
		
		var sel = window.getSelection();
		sel.addRange(myrange);

		//document.execCommand('copy', false, null);
		var successful = document.execCommand('copy');

		var msg = successful ? 'successful' : 'unsuccessful';
		//alert('Copy image command was ' + msg);

	}
</script>

 
 <!DOCTYPE html>
<html>
	<head>
		<title>You are in Room <?php echo $_SESSION["adminRoomID"] ?></title>
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
		<style>
			.wrapper {
				margin: auto;
				width: 500px;
				padding: 25px;
			}
		</style>
	</head>
	<body>
		<div class="wrapper">
			<h1>You are in Room <?php echo $_SESSION["adminRoomID"] ?></h1>
			<br>
			<!-- Input qr code here -->
			<div class = "QRContainer">
				<img id = "imgQR" src = "<?php echo $qrurl;?>">
				<br>
				<br>
				<button onclick="CopyImageById('imgQR')">Copy QR to Clipboard</button>
			</div>

			<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
				<!-- <div>
					<label>Enter room number</label>
					<input type="number" min="0" name="userroom" class="form-control">
					<span class="help-block"></span>
				</div> -->
				<br>
				<br>
                <table class="table">
				<thread>
					<tr>
					<th scope="col">Username</th>
					<th scope="col">Duration</th>
					<th scope="col">Clock In Time</th>
					<th scope="col">Clock Out Time</th>
					</tr>
				</thread>
				<tbody>
					<?php
					$name = $_SESSION["adminRoomID"];
					// Check connection
					if ($connection->connect_error) {
					die("Connection failed: " . $connection->connect_error);
					}
					$sql2 = "SELECT username, checkIN, checkOUT FROM roomdata WHERE roomID = '$name'";
					$result = $connection->query($sql2);
	
					if ($result->num_rows > 0) {
					// output data of each row
					while($row = $result->fetch_assoc()) {
						$datetime1 = new DateTime($row["checkIN"]);//start time
						$datetime2 = new DateTime($row["checkOUT"]);//end time
						$interval = $datetime1->diff($datetime2);

						echo "<tr><th scope='row'>" . $row["username"]. "</th><td>" . $interval->format('%i minutes %s seconds') . "</td><td>" . $row["checkIN"] . "</td><td>" . $row["checkOUT"] . "</td></tr>";
					}
					echo "</table>";
					} else { echo "0 results"; }
					$connection->close();
					?>
					</tbody>
				</table>
				<br>
				<div>
					<input type="submit" class="btn btn-warning" value="Leave">
				</div>
				<br>
				<br>
				<div>
					<a href="main_admin.php" class="btn btn-primary">Back</a>
				</div>
            </form>
		</div>
	</body>
</html>
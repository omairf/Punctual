<?php
// Function for alert box pop up
function phpAlert($msg) {
	echo '<script>alert("' . $msg . '")</script>';
}

// Start session
session_start();
 
// Linking config file
require_once "config.php";

// When form is submitted, do this
if($_SERVER["REQUEST_METHOD"] == "POST") {
	
	// Checking if value is blank, if not, then process and calculate values. Output will occur in an alert box
	if(empty(trim($_POST["userroom"]))) {
    	phpAlert("Value cannot be blank");
    }
    else {
        $mysql = "SELECT id FROM rooms WHERE roomid = ?";
        
       	if($stmt = mysqli_prepare($connection, $mysql)) {
           	mysqli_stmt_bind_param($stmt, "s", $user_parameter);
           	$user_parameter = trim($_POST["userroom"]);
           
           	if(mysqli_stmt_execute($stmt)) {
               	mysqli_stmt_store_result($stmt);
                
               	if(mysqli_stmt_num_rows($stmt) == 1) {                    
                   	mysqli_stmt_bind_result($stmt, $userroom);
                    
                   	if(mysqli_stmt_fetch($stmt)) {
                       	phpAlert("Joined room!!");
						mysqli_stmt_close($stmt);

						$sql2 = "INSERT INTO roomdata (userID, username, checkIN, roomID) VALUES (?, ?, ?, ?)";

    					$stmt = $connection->prepare($sql2);

						$cI = date('Y-m-d H:i:s');
						$user_parameter2 = trim($_POST["userroom"]);

						$stmt->bind_param("isss", $_SESSION["id"],$_SESSION["username"],$cI, $user_parameter);
						$stmt->execute();
						$stmt->close();

						$_SESSION["roomID"] = $user_parameter;
						header("location: room.php");
                   	}
               	}
               	else {
                   	phpAlert("That room does not exist, try creating it first");
               	}
           	}
           	else {
               	phpAlert("Something bad happened :( Try refreshing the page");
           	}
        }
    	mysqli_close($connection);
	}
}
?>
 
<!DOCTYPE html>
<html>
	<head>
		<title>Join a Room</title>
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
			<h1>Join a Room!!</h1>
			<br>
			<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
				<div>
					<label>Enter room number</label>
					<input type="number" min="0" name="userroom" class="form-control">
					<span class="help-block"></span>
				</div>
				<br>
				<div>
					<input type="submit" class="btn btn-warning" value="Join!">
				</div>
				<br>
				<br>
				<div>
					<a href="main.php" class="btn btn-primary">Back</a>
				</div>
			</form>
		</div>
	</body>
</html>

<?php
// Function for alert box pop up
function phpAlert($msg) {
	echo '<script>alert("' . $msg . '")</script>';
}

// Start session
session_start();
 
// Linking config file
require_once "config.php";

// Variables to be used
$check1 = false;
$roomkey = "";

// When form is submitted, do this
if($_SERVER["REQUEST_METHOD"] == "POST") {
	
	// Checking if value is blank, if not, then process and calculate values. Output will occur in an alert box
	if(empty(trim($_POST["roomkey"]))) {
    	phpAlert("Value cannot be blank");
    }
    else {
    	$roomkey = trim($_POST["roomkey"]);
    	
    	if ((strlen($roomkey)) != 6) {
    		phpAlert("Room key must be six digits long!");
    	}
    	else {
		    // If fields pass both checks, then validate using sql statements to pull data from database table. Main purpose here is to check if the username is unique
		    $sql = "SELECT id FROM rooms WHERE roomid = ?";
		    
		    if($stmt = mysqli_prepare($connection, $sql)) {
		        mysqli_stmt_bind_param($stmt, "s", $user_parameter);
		        $user_parameter = trim($_POST["roomkey"]);
		        
		        if(mysqli_stmt_execute($stmt)) {
		            mysqli_stmt_store_result($stmt);
		            
		            if(mysqli_stmt_num_rows($stmt) == 1) {
		                phpAlert("Room ID is taken, try a different one");
		            }
		            else {
		                $roomkey = trim($_POST["roomkey"]);
						$_SESSION["adminRoomID"] = $roomkey;
		                $check1 = true;
		            }
		        }
		        else {
		            echo "Something bad happened :( Try refreshing the page";
		        }
		        mysqli_stmt_close($stmt);
		    }
		}
	}
	
	if($check1) {
        $sql = "INSERT INTO rooms (roomid) VALUES (?)";
         
        if($stmt = mysqli_prepare($connection, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $user_parameter);
            $user_parameter = $roomkey;
            
            if(mysqli_stmt_execute($stmt)) {
                phpAlert("Room initialized!");
            }
            else {
                phpAlert("Something bad happened :( Try refreshing the page");
            }

            mysqli_stmt_close($stmt);
        }
		header("location: room_admin.php");
		
		// $sql2 = ("CREATE TABLE roomdata (
		// 	id INT AUTO_INCREMENT PRIMARY KEY,
		// 	username VARCHAR(30) NOT NULL,
		// 	checkIN TIMESTAMP,
		// 	checkOUT TIMESTAMP)");
         
        // if($stmt = mysqli_prepare($connection, $sql2)) {
        //     // mysqli_stmt_bind_param($stmt, "s", $user_parameter);
        //     // $user_parameter = $roomkey;
            
        //     if(mysqli_stmt_execute($stmt)) {
        //         phpAlert("Room created!");
        //     }
        //     else {
        //         phpAlert("Something bad happened :( Try refreshing the page");
        //     }

        //     mysqli_stmt_close($stmt);
        // }
    }
    
    mysqli_close($connection);
}
?>
 
<!DOCTYPE html>
<html>
	<head>
		<title>Create a Room</title>
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
			<h1>Create a Room!!</h1>
			<br>
			<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
				<div>
					<label>Enter a Room Number</label>
					<input type="number" min="0" name="roomkey" class="form-control">
					<span class="help-block"></span>
				</div>
				<br>
				<div>
					<input type="submit" class="btn btn-warning" value="Create!">
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

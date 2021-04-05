<?php
// Function for alert box pop up
function phpAlert($msg) {
	echo '<script>alert("' . $msg . '")</script>';
}

// Start session
session_start();
 
// Linking config file
require_once "config.php";

	$roomnum = null;
	$roomnum = $_GET["roomno"];
	//echo "<h2>roomnum:".$roomnum."</h2>";

	if ($roomnum != null){
		$mysql = "SELECT id FROM rooms WHERE roomid = ?";
        
       	if($stmt = mysqli_prepare($connection, $mysql)) {
           	mysqli_stmt_bind_param($stmt, "s", $user_parameter);
           	$user_parameter = trim($roomnum);
           
           	if(mysqli_stmt_execute($stmt)) {
               	mysqli_stmt_store_result($stmt);
                
               	if(mysqli_stmt_num_rows($stmt) == 1) {                    
                   	mysqli_stmt_bind_result($stmt, $roomnum);
                    
                   	if(mysqli_stmt_fetch($stmt)) {
                       	phpAlert("Joined room!!");
						mysqli_stmt_close($stmt);

						$sql2 = "INSERT INTO roomdata (id, username, checkIN, roomID) VALUES (?, ?, ?, ?)";

    					$stmt = $connection->prepare($sql2);

						$cI = date('Y-m-d H:i:s');
						$user_parameter2 = trim($roomnum);

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

           	// mysqli_stmt_close($stmt);
		}
		mysqli_close($connection);
	}

?>
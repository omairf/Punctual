<?php

// Function for alert box pop up
function phpAlert($msg) {
	echo '<script>alert("' . $msg . '")</script>';
}
// Start session 
session_start();
 
// Checks if user is logged in, if they aren't then redirect
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

// Linking config file
require_once "config.php";

// When form is submitted, do this
if($_SERVER["REQUEST_METHOD"] == "post"){
            $roomIDnum = $_SESSION["adminRoomID"];
            $sql2 = "SELECT username, checkIN, checkOUT FROM roomdata WHERE roomID = ?";
            $arr = array();
            if($stmt = mysqli_prepare($connection, $sql2)) {
		        mysqli_stmt_bind_param($stmt, "s", $_SESSION["adminRoomID"]);
		        
		        if(mysqli_stmt_execute($stmt)) {
		            mysqli_stmt_store_result($stmt);
		            
		            if(mysqli_stmt_num_rows($stmt) > 0) {
                        while($row = mysqli_fetch_array($stmt, MYSQLI_ASSOC)){
                            $arr = $row;
                        }
                        $_SESSION['data'] = $arr;
		            }
		            else {
                        echo "Something bad happened :( Try refreshing the page";

		            }
		        }
		        else {
		            echo "Something bad happened again :( Try refreshing the page";
		        }
		        mysqli_stmt_close($stmt);
		    }
            // $stmt = $connection->prepare($sql2);

            // $stmt->bind_param("s", $_SESSION["adminRoomID"]);
            // $stmt->execute();
            // $stmt->close();
}
?>
 
 <!DOCTYPE html>
<html>
	<head>
		<title>You are in Room #</title>
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
			<h1>You are in Room <?php echo $_SESSION["adminRoomID"] ?>!</h1>
			<br>
			<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
				<!-- <div>
					<label>Enter room number</label>
					<input type="number" min="0" name="userroom" class="form-control">
					<span class="help-block"></span>
				</div> -->
                <!-- <div>
                    <table>
                    <thead>
                        <tr><th>Username</th><th>Check In</th><th>Check Out</th></tr>
                    </thead>
                    <tbody>

                    <?php  
                    foreach ($_SESSION['data'] as $r) { ?>
                                <tr><td><?php echo $r['username']; ?></td>   <td><?php echo $r['checkIN']; ?></td>   <td><?php echo $r['checkOUT']; ?></td></tr>

                    <?php  } ?>



                    </tbody>
                    </table>
                </div> -->
				<br>
				<div>
					<input type="submit" class="btn btn-warning" value="Print Report!">
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
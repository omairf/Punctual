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

// Variables to be used
$userroom = "";

// When form is submitted, do this
if($_SERVER["REQUEST_METHOD"] == "POST") {
	
            $sql2 = "UPDATE roomdata SET checkOUT=? WHERE username=?";

            $stmt = $connection->prepare($sql2);

            $cO = date('Y-m-d H:i:s');
            $user_parameter2 = trim($_POST["userroom"]);

            $stmt->bind_param("ss", $cO, $_SESSION["username"]);
            $stmt->execute();
            $stmt->close();
            header("location: main.php");
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
			<h1>You are in Room <?php echo $_SESSION["roomID"] ?>!</h1>
			<br>
			<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
				<!-- <div>
					<label>Enter room number</label>
					<input type="number" min="0" name="userroom" class="form-control">
					<span class="help-block"></span>
				</div> -->
				<br>
				<div>
					<input type="submit" class="btn btn-warning" value="Leave">
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
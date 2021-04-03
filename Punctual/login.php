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
$username = "";
$password = "";
$usercheck = false;
$passcheck = false;
 
// When form is submitted, do this
if($_SERVER["REQUEST_METHOD"] == "POST") {
 
    // Empty username check
    if(empty(trim($_POST["username"]))) {
    	phpAlert("Enter Username");
    }
    else {
        $username = trim($_POST["username"]);
        $usercheck = true;
    }
    
    // Empty password check
    if(empty(trim($_POST["password"]))) {
        phpAlert("Enter Password");
    }
    else {
        $password = trim($_POST["password"]);
        $passcheck = true;
    }
    
    // If fields pass both checks, then validate using sql statements to pull data from database table. Store the values and redirect the user. Else statements focus on error handling at different stages
    if($usercheck && $passcheck) {
        $mysql = "SELECT id, username, password FROM users WHERE username = ?";
        
        if($stmt = mysqli_prepare($connection, $mysql)) {
            mysqli_stmt_bind_param($stmt, "s", $user_parameter);
            $user_parameter = $username;
            
            if(mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1) {                    
                    mysqli_stmt_bind_result($stmt, $id, $username, $password2);
                    
                    if(mysqli_stmt_fetch($stmt)) {
                        if(password_verify($password, $password2)){

                            session_start();
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;                            
                            

                            header("location: main.php");
                        }
                        else {
                            phpAlert("The password is incorrect");
                        }
                    }
                }
                else {
                    phpAlert("That username does not exist, did you forget to register?");
                }
            }
            else {
                phpAlert("Something bad happened :( Try refreshing the page");
            }

            mysqli_stmt_close($stmt);
        }
    }
    mysqli_close($connection);
}
?>
 
<!DOCTYPE html>
<html>
	<head>
		<title>Login Page</title>
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
		    <h1>Login</h1>
		    <br>
		    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
		        <div>
		            <label>Username</label>
		            <input type="text" name="username" class="form-control">
		            <span class="help-block"></span>
		        </div>    
		        <br>
		        <div>
		            <label>Password</label>
		            <input type="password" name="password" class="form-control">
		            <span class="help-block"></span>
		        </div>
		        <br>
		        <div class="form-group">
		            <input type="submit" class="btn btn-primary" value="Login">
		        </div>
		        <p>First time here? <a href="register.php">Click here to sign up.</a></p>
		    </form>
		</div>    
	</body>
</html>

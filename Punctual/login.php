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
$useradmin = "";
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
        $mysql = "SELECT id, username, password, admin FROM users WHERE username = ?";
        
        if($stmt = mysqli_prepare($connection, $mysql)) {
            mysqli_stmt_bind_param($stmt, "s", $user_parameter);
            $user_parameter = $username;
            
            if(mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1) {                    
                    mysqli_stmt_bind_result($stmt, $id, $username, $password2, $useradmin);
                    
                    if(mysqli_stmt_fetch($stmt)) {
                        if(password_verify($password, $password2)){

                            session_start();
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;
                            $_SESSION["adminRoomID"] = null;
                            $_SESSION["roomID"] = null;
                            
                            if ((strcmp($useradmin,"0")) == 0) {
                            	header("location: main.php");
                            }
                            else if ((strcmp($useradmin,"1")) == 0) {
                            	header("location: main_admin.php");
                            }
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
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="main.css">
	</head>
    <body>
        <nav class="navbar navbar-expand-md">
            <a class="navbar-brand" href="#">Punctual</a>
            <button class="navbar-toggler navbar-dark" type="button" data-toggle="collapse" data-target="#main-navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
                <div class="collapse navbar-collapse" id="main-navigation">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link" href="home.php">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="login.php">Login</a>
                        </li>
                    </ul>
                </div>
        </nav>
        <header class="page-header header container-fluid">
            <div class="overlay"></div>
            <div class="description">
                <!-- code goes here -->
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
            </div>
        </header>
        <script src="main.js"></script>
        <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js" integrity="sha384-w1Q4orYjBQndcko6MimVbzY0tgp4pWB4lZ7lr30WKz0vr/aWKhXdBNmNb5D92v7s" crossorigin="anonymous"></script>
    </body>
</html>

<?php

// Function for alert box pop up
function phpAlert($msg) {
	echo '<script>alert("' . $msg . '")</script>';
}

// Linking config file
require_once "config.php";
 
// Variables to be used
$username = "";
$password = "";
$password2 = "";
$admin = "";
$usercheck = false;
$passcheck = false;
$passcheck2 = false;
$admincheck = false;
 
// When form is submitted, do this
if($_SERVER["REQUEST_METHOD"] == "POST") {
 
    // Empty username check
    if(empty(trim($_POST["username"]))) {
        phpAlert("Enter username");
	}
    else {
        // If fields pass both checks, then validate using sql statements to pull data from database table. Main purpose here is to check if the username is unique
        $sql = "SELECT id FROM users WHERE username = ?";
        
        if($stmt = mysqli_prepare($connection, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $user_parameter);
            $user_parameter = trim($_POST["username"]);
            
            if(mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1) {
                    phpAlert("Username is taken, try a different one");
                }
                else {
                    $username = trim($_POST["username"]);
                    $usercheck = true;
                }
            }
            else {
                phpAlert("Something bad happened :( Try refreshing the page");
            }
            mysqli_stmt_close($stmt);
        }
	}
    
    // Empty password check
    if(empty(trim($_POST["password"]))) {
        phpAlert("Password field cannot be blank");   
    }
    else {
        $password = trim($_POST["password"]);
        $passcheck = true;
    }
    
    // Checking that both passwords match
    if(empty(trim($_POST["password2"]))) {
        phpAlert("Enter the password again to confirm");
    }
    else {
        $password2 = trim($_POST["password2"]);
        if($passcheck && ($password != $password2)) {
            phpAlert("Passwords do not match, please try again!");
        }
        else {
        	$passcheck2 = true;
        }
    }
    
    if(empty(trim($_POST['admin']))) {
		phpAlert("Choose a value for 'Admin Role'");
    }
    else {
        $admincheck = true;
		if ((strcmp($_POST['admin'], "Yes")) == 0) {
			$admin = "1";
		}
		else if ((strcmp($_POST['admin'], "No")) == 0) {
			$admin = "0";
		}
    }
    
    // Making sure all fields pass all the checks, if they do then insert data into the database table and redirect the user
    if($usercheck && $passcheck && $passcheck2 && $admincheck){
        $sql = "INSERT INTO users (username, password, admin) VALUES (?, ?, ?)";
         
        if($stmt = mysqli_prepare($connection, $sql)) {
            mysqli_stmt_bind_param($stmt, "ssi", $user_parameter, $pass_parameter, $admin_parameter);
            $user_parameter = $username;
            $pass_parameter = password_hash($password, PASSWORD_DEFAULT);
            $admin_parameter = $admin;
            
            if(mysqli_stmt_execute($stmt)) {
                header("location: login.php");
            }
            else {
                echo "Something bad happened :( Try refreshing the page";
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
		<title>Register Page</title>
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
		    <h1>Sign Up</h1>
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
		        <div>
		            <label>Confirm Password</label>
		            <input type="password" name="password2" class="form-control">
		            <span class="help-block"></span>
		        </div>
		        <br>
		        <div>
		        	<label>Admin Role</label>
		        	<br>
		        	<input type="radio" id="adminyes" name="admin" value="Yes">
					<label for="Yes">Yes</label><br>
					<input type="radio" id="adminno" name="admin" value="No">
					<label for="No">No</label><br>
		        <br>
		        <div class="form-group">
		            <input type="submit" class="btn btn-primary" value="Submit">
		        </div>
		        <p>Need to login? <a href="login.php">Click here.</a></p>
		    </form>
		</div>    
	</body>
</html>

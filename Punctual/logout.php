<?php
// Session start
session_start();
 
// Resetting variables for session
$_SESSION = array();
 
// Destroy session
session_destroy();
 
// Redirection
header("location: login.php");
exit;
?>

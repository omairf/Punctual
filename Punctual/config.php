<?php
 
// Connecting to database and table
$connection = mysqli_connect('punctual-db.cazjryxuuisu.us-east-1.rds.amazonaws.com', 'Group9', 'Notpunctual', 'Punctual_db');
 
// Error handling for failed connection
if($connection == false) {
    die("Connection Error! Please try again later.");
}
?>

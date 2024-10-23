<?php
$host = "localhost"; 
$username = "root"; 
$password = ""; 
$dbname = "mounac53_election_management"; 

// Create a new connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
// Note: Removed the echo statement for successful connection
?>

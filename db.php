<?php
$host = "localhost";     // Usually localhost
$username = "root";      // Your DB username
$password = "";          // Your DB password (empty if none)
$database = "ottostore";   // Replace with your actual database name

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

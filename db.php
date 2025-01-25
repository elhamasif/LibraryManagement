<?php
// Database connection settings
$host = "localhost";
$username = "root";
$password = ""; // Default password for XAMPP is empty
$database = "library_system";

// Create a connection
$conn = new mysqli($host, $username, $password, $database);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

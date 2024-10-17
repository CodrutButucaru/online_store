<?php
// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "magazin_online";

// Create a new connection to the database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check if the connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error); // Display error and terminate if connection fails
}

// Set the character set to UTF-8 to support special characters
$conn->set_charset("utf8mb4");
?>

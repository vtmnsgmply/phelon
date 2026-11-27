<?php
// Database creation script
$host = "localhost";
$username = "me";
$password = "Shad0owhack3rr@@";
$database = "phelon";

// Connect to MySQL server (without database)
$conn = mysqli_connect($host, $username, $password);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Create database
$sql = "CREATE DATABASE IF NOT EXISTS " . $database;

if (mysqli_query($conn, $sql)) {
    echo "Database '" . $database . "' created successfully or already exists!";
} else {
    echo "Error creating database: " . mysqli_error($conn);
}

mysqli_close($conn);
?>

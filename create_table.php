<?php
// Table creation script
$host = "localhost";
$username = "me";
$password = "Shad0owhack3rr@@";
$database = "phelon";

// Connect to MySQL database
$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Create table
$sql = "CREATE TABLE IF NOT EXISTS customers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    firstName VARCHAR(100) NOT NULL,
    lastName VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    email VARCHAR(100),
    product VARCHAR(200),
    total DECIMAL(10, 2),
    transactionId VARCHAR(100),
    date_created TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    access_code VARCHAR(100)
)";

if (mysqli_query($conn, $sql)) {
    echo "Table 'customers' created successfully or already exists!";
} else {
    echo "Error creating table: " . mysqli_error($conn);
}

mysqli_close($conn);
?>

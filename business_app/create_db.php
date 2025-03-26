<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Management</title>
    <!-- Link to the CSS file -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<?php
$servername = "localhost";
$username = "root";
$password = "";

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if database exists
$db_check_sql = "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = 'business_db'";
$result = $conn->query($db_check_sql);

if ($result->num_rows == 0) {
    // Create database if it does not exist
    $sql = "CREATE DATABASE business_db";
    if ($conn->query($sql) === TRUE) {
        echo "Database created successfully<br>";
    } else {
        echo "Error creating database: " . $conn->error;
    }
}

// Select the database
$conn->select_db('business_db');

// Check if tables exist, and create them if not
$clientsTable = "CREATE TABLE IF NOT EXISTS Clients (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255),
    phone_number VARCHAR(20),
    status ENUM('Active', 'Inactive') DEFAULT 'Active'
)";

$paymentsTable = "CREATE TABLE IF NOT EXISTS Payments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    client_id INT,
    amount_paid DECIMAL(10, 2),
    payment_date DATE,
    next_payment_due DATE,
    FOREIGN KEY (client_id) REFERENCES Clients(id)
)";

$servicePlansTable = "CREATE TABLE IF NOT EXISTS ServicePlans (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255),
    amount DECIMAL(10, 2),
    duration INT -- in days
)";

if ($conn->query($clientsTable) === TRUE && $conn->query($paymentsTable) === TRUE && $conn->query($servicePlansTable) === TRUE) {
    echo "Tables created successfully!";
} else {
    echo "Error creating tables: " . $conn->error;
}

// Redirect to the management page after setup
header('Location: view_clients.php');
exit();

$conn->close();
?>

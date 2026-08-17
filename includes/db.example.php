<?php
// Database Configuration
// Copy this file as db.php and fill in your credentials
// Command: cp db.example.php db.php

$host     = "localhost";         // Database host
$user     = "root";              // Database username
$password = "";                  // Database password
$database = "blog_db";           // Database name

try {
    $conn = mysqli_connect($host, $user, $password, $database);
} catch (mysqli_sql_exception $e) {
    die("Database Connection Error: " . $e->getMessage());
}
?>

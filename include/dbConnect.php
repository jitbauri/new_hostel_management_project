<?php
$host = 'localhost';       // Database host (usually localhost)
$dbname = 'hostel_management';     // Your database name
$username = 'root';        // Your database username
$password = '';            // Your database password (empty if using XAMPP default)

$conn = mysqli_connect($host, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>

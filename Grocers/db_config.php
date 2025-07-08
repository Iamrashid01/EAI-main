<?php
$host = "localhost";
$user = "root"; // Change if you have another MySQL user
$password = ""; // Change if your MySQL has a password
$database = "grocers"; // Replace with your database name

$conn = new mysqli($host, $user, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

<?php

$host = 'localhost:3306';
$user = 'root';
$pass = '';
$db   = 'qr_feedback';

// Create connection
$conn = mysqli_connect($host, $user, $pass, $db);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
return $conn;
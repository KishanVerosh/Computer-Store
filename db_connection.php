<?php
// Database connection
$servername = "sql313.infinityfree.com";
$username = "if0_38300532";
$password = "0QZrmFZsjDFU";
$dbname = "if0_38300532_store";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

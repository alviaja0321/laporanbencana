<?php
$host = 'localhost';
$user = 'root'; // Default user XAMPP is root
$pass = ''; // Default password is empty
$db = 'db_bencana'; // Change if the database name is different

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
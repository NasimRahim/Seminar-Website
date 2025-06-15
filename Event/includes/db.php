<?php
$host = 'localhost';
$dbname = 'event'; // use the exact database name
$user = 'root';
$pass = ''; // default XAMPP password is empty

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "Connected successfully"; // uncomment for testing
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>

<?php
$conn = new mysqli('localhost', 'root', '', 'cafe_dopamine');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Database connected successfully!";
$conn->close();
?>
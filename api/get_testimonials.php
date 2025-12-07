<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
require_once '../includes/database.php';

$sql = "SELECT * FROM testimonials WHERE is_approved = 1 ORDER BY created_at DESC";
$result = $conn->query($sql);

$testimonials = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $testimonials[] = $row;
    }
}

echo json_encode($testimonials);
?>
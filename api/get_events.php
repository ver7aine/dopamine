<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
require_once '../includes/database.php';

$sql = "SELECT * FROM events WHERE is_upcoming = 1 ORDER BY date, time";
$result = $conn->query($sql);

$events = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $events[] = $row;
    }
}

echo json_encode($events);
?>
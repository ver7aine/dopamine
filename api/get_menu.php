<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
require_once '../includes/database.php';

$sql = "SELECT * FROM menu_items WHERE is_active = 1 ORDER BY category, name";
$result = $conn->query($sql);

$menu_items = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $menu_items[] = $row;
    }
}

echo json_encode($menu_items);
?>
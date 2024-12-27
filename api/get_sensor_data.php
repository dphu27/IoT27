<?php
include '../includes/db_connect.php';

$sql = "SELECT * FROM sensordatas ORDER BY timestamp DESC LIMIT 10";
$result = $conn->query($sql);

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

header('Content-Type: application/json');
echo json_encode($data);
?>
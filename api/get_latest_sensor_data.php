<?php
header('Access-Control-Allow-Origin: *'); // Cho phép mọi nguồn (hoặc chỉ định cụ thể)
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

include '../includes/db_connect.php';

// Lấy dữ liệu mới nhất từ bảng sensor_data
$sql = "SELECT * FROM sensordatas ORDER BY timestamp DESC LIMIT 1";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    $data = $result->fetch_assoc();
    header('Content-Type: application/json');
    echo json_encode($data);
} else {
    echo json_encode(['error' => 'No data found']);
}
$conn->close();
?>
<?php
include '../includes/db_connect.php'; // Kết nối đến cơ sở dữ liệu

// Lấy dữ liệu từ bảng device_states
$sql = "SELECT device, state, timestamp FROM device_states ORDER BY timestamp DESC";
$result = $conn->query($sql);

$data = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row; // Thêm từng bản ghi vào mảng dữ liệu
    }
}

header('Content-Type: application/json');
echo json_encode($data); // Trả về dữ liệu dưới dạng JSON

$conn->close();
?>
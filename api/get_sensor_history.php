<?php
header('Access-Control-Allow-Origin: *'); // Cho phép mọi nguồn (hoặc chỉ định cụ thể)
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

include '../includes/db_connect.php';

// Lấy dữ liệu lịch sử (giới hạn 50 bản ghi)
$sql = "SELECT * FROM sensordatas ORDER BY timestamp DESC LIMIT 50";
$result = $conn->query($sql);

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

header('Content-Type: application/json');
echo json_encode($data);
$conn->close();
?>
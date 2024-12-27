<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

include 'db_connect.php'; // Kết nối đến cơ sở dữ liệu

// Kiểm tra xem có dữ liệu POST không
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $device = $_POST['device']; // Tên thiết bị
    $state = $_POST['state']; // Trạng thái (ON/OFF)

    // Kiểm tra xem biến có giá trị không
    if (empty($device) || empty($state)) {
        echo json_encode(['success' => false, 'message' => 'Device or state is empty']);
        exit;
    }

    // Lưu trạng thái vào cơ sở dữ liệu
    $sql = "INSERT INTO device_states (device, state, timestamp) VALUES ('$device', '$state', NOW())";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(['success' => true, 'message' => 'State saved successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $conn->error]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}

$conn->close();
?>
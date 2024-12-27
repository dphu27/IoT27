<?php
include 'db_connect.php';

// Lấy dữ liệu từ ESP32 gửi lên
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $temperature = $_POST['temperature'];
    $humidity = $_POST['humidity'];
    $light = $_POST['light'];

    // Lưu vào cơ sở dữ liệu
    $sql = "INSERT INTO sensordatas (temperature, humidity, light) VALUES ('$temperature', '$humidity', '$light')";

    if ($conn->query($sql) === TRUE) {
        echo "Dữ liệu đã được lưu thành công!";
    } else {
        echo "Lỗi: " . $sql . "<br>" . $conn->error;
    }
}
$conn->close();
?>
<?php
$servername = "localhost"; // Hoặc IP của máy chủ MySQL
$username = "root";        // Tên đăng nhập MySQL
$password = "";            // Mật khẩu MySQL
$dbname = "iot_db";        // Tên cơ sở dữ liệu

// Kết nối tới MySQL
$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}
?>
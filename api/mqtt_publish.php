<?php
require('../includes/phpMQTT.php'); // Đảm bảo bạn đã tải phpMQTT vào dự án

$server = "192.168.0.3"; // Địa chỉ IP của MQTT broker
$port = 1983;
$username = "dinhphu";
$password = "1234";
$client_id = "PHP_MQTT_Client";

$mqtt = new Bluerhinos\phpMQTT($server, $port, $client_id);

if ($mqtt->connect(true, NULL, $username, $password)) {
    // Kiểm tra giá trị POST
    if (isset($_POST['device']) && isset($_POST['state'])) {
        $device = $_POST['device'];
        $state = $_POST['state'];

        $topic = "home/devices/$device";
        $mqtt->publish($topic, $state, 0);

        $mqtt->close();
        echo json_encode(['success' => true, 'message' => "Command sent to $device: $state"]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Missing parameters: device or state']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to connect to MQTT broker']);
}
?>
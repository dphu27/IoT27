const express = require('express');
const bodyParser = require('body-parser');
const mqtt = require('mqtt');
const swaggerUi = require('swagger-ui-express');
const swaggerSpec = require('./swagger-config'); // Cấu hình Swagger

// Cấu hình MQTT và server
const MQTT_BROKER_URL = 'mqtt://192.168.0.3:1983';
const MQTT_USERNAME = 'dinhphu';
const MQTT_PASSWORD = '1234';
const PORT = 4000;

const app = express();
app.use(bodyParser.json());
app.use(bodyParser.urlencoded({ extended: true }));

const cors = require('cors');
app.use(cors());

// Kết nối MQTT
const mqttClient = mqtt.connect(MQTT_BROKER_URL, {
    username: MQTT_USERNAME,
    password: MQTT_PASSWORD,
});

mqttClient.on('connect', () => {
    console.log('MQTT connected');
});

// Tích hợp Swagger UI
app.use('/api-docs', swaggerUi.serve, swaggerUi.setup(swaggerSpec));

// Endpoint điều khiển thiết bị
/**
 * @swagger
 * /control:
 *   post:
 *     summary: Control IoT device
 *     description: Send ON/OFF commands to IoT devices via MQTT
 *     requestBody:
 *       required: true
 *       content:
 *         application/json:
 *           schema:
 *             type: object
 *             properties:
 *               device:
 *                 type: string
 *                 description: The device identifier (e.g., "quat", "den")
 *               state:
 *                 type: string
 *                 description: The device state (e.g., "ON", "OFF")
 *     responses:
 *       200:
 *         description: Command sent successfully
 *       400:
 *         description: Missing parameters
 *       500:
 *         description: Failed to send command
 */
app.post('/control', (req, res) => {
    const { device, state } = req.body;
    const topic = `home/devices/${device}`;
    mqttClient.publish(topic, state, (err) => {
        if (err) {
            return res.status(500).json({ success: false, message: 'Failed to send MQTT message' });
        }
        res.json({ success: true, message: `Command sent to ${device}: ${state}` });
    });
});

// Start server
app.listen(PORT, () => {
    console.log(`Server is running on http://localhost:${PORT}`);
    console.log(`API documentation available at http://localhost:${PORT}/api-docs`);
});

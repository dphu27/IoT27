#include <WiFi.h>          // Thư viện WiFi cho ESP32
#include <PubSubClient.h>   // Thư viện MQTT
#include <DHT.h>            // Thư viện cảm biến DHT
#include <HTTPClient.h>

#define DHTPIN 5          // Chân GPIO5 cho cảm biến DHT11 (điều chỉnh nếu cần)
#define DHTTYPE DHT11      // Chọn loại cảm biến DHT11
DHT dht(DHTPIN, DHTTYPE);

int ldrPin = 34;          // Chân ADC1_6 (GPIO34) cho LDR trên ESP32 (điều chỉnh nếu cần)
int ldrValue = 0;         // Biến để lưu giá trị từ LDR

// Chân kết nối cho các thiết bị (điều chỉnh tùy theo phần cứng của bạn)
#define LIGHT1_PIN 13      // Chân GPIO13 cho Đèn
#define LIGHT2_PIN 14      // Chân GPIO14 cho Quạt
#define LIGHT3_PIN 26      // Chân GPIO26 cho Điều hòa


// Thông tin Wi-Fi và MQTT
const char* ssid = "PhanNa";
const char* password = "yeunanana";
const char* mqtt_server = "192.168.0.3";  // Địa chỉ IP của MQTT broker
const char* mqtt_username = "dinhphu";
const char* mqtt_password = "1234";
const char* serverName = "http://192.168.0.3/IOT/includes/save_data.php";

// Khởi tạo WiFi và MQTT client
WiFiClient espClient;
PubSubClient client(espClient);

// Hàm kết nối WiFi
void setup_wifi() {
  delay(10);
  Serial.println();
  Serial.print("Dang ket noi voi ");
  Serial.println(ssid);
  WiFi.begin(ssid, password);

  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
  Serial.println("");
  Serial.println("WiFi đa ket noi");
  Serial.println("Dia chi IP: ");
  Serial.println(WiFi.localIP());
}

// Hàm xử lý tin nhắn MQTT nhận được
void callback(char* topic, byte* payload, unsigned int length) {
    String message;
    for (int i = 0; i < length; i++) {
        message += (char)payload[i];
    }

    Serial.print("Received message on topic: ");
    Serial.print(topic);
    Serial.print(". Message: ");
    Serial.println(message);

    // Kiểm tra từng topic và thực hiện hành động tương ứng
    if (strcmp(topic, "home/devices/den") == 0) {
        if (message == "ON") {
            digitalWrite(LIGHT1_PIN, HIGH);
            Serial.println("Light turned ON");
        } else if (message == "OFF") {
            digitalWrite(LIGHT1_PIN, LOW);
            Serial.println("Light turned OFF");
        }
    } else if (strcmp(topic, "home/devices/quat") == 0) {
        if (message == "ON") {
            digitalWrite(LIGHT2_PIN, HIGH);
            Serial.println("Fan turned ON");
        } else if (message == "OFF") {
            digitalWrite(LIGHT2_PIN, LOW);
            Serial.println("Fan turned OFF");
        }
    } else if (strcmp(topic, "home/devices/dieuhoa") == 0) {
        if (message == "ON") {
            digitalWrite(LIGHT3_PIN, HIGH);
            Serial.println("AC turned ON");
        } else if (message == "OFF") {
            digitalWrite(LIGHT3_PIN, LOW);
            Serial.println("AC turned OFF");
        }
    }

  if (strcmp(topic, "home/devices/all") == 0) {
    if (message == "ON") {
      digitalWrite(LIGHT1_PIN, HIGH);
      digitalWrite(LIGHT2_PIN, HIGH);
      digitalWrite(LIGHT3_PIN, HIGH);
      client.publish("home/devices/den/status", "ON");
      client.publish("home/devices/quat/status", "ON");
      client.publish("home/devices/dieuhoa/status", "ON");
      Serial.println("Tat ca cac thiet bi da bat");
    } else if (message == "OFF") {
      digitalWrite(LIGHT1_PIN, LOW);
      digitalWrite(LIGHT2_PIN, LOW);
      digitalWrite(LIGHT3_PIN, LOW);
      client.publish("home/devices/den/status", "OFF");
      client.publish("home/devices/quat/status", "OFF");
      client.publish("home/devices/dieuhoa/status", "OFF");
      Serial.println("Tat ca cac thiet bi da tat");
    }
  }
}

// Hàm kết nối lại với MQTT broker nếu mất kết nối
void reconnect() {
  while (!client.connected()) {
    Serial.print("Dang ket noi lai voi MQTT...");
    if (client.connect("ESP32Client", mqtt_username, mqtt_password)) {
      Serial.println("đa ket noi");

      // Đăng ký các chủ đề để điều khiển thiết bị
      client.subscribe("home/devices/den");
      client.subscribe("home/devices/quat");
      client.subscribe("home/devices/dieuhoa");
      client.subscribe("home/devices/all");
    } else {
      Serial.print("that bai, rc=");
      Serial.print(client.state());
      Serial.println(" thu lai sau 5 giay");
      delay(5000);
    }
  }
}

void setup() {
  Serial.begin(115200);
  setup_wifi();
  client.setServer(mqtt_server, 1983);
  client.setCallback(callback);

  // Khởi tạo cảm biến DHT
  dht.begin();

  // Đặt chế độ cho các chân điều khiển thiết bị
  pinMode(LIGHT1_PIN, OUTPUT);  // Đèn
  pinMode(LIGHT2_PIN, OUTPUT);  // Quạt
  pinMode(LIGHT3_PIN, OUTPUT);  // Điều hòa
}

void loop() {
  if (!client.connected()) {
    reconnect();  // Kết nối lại nếu cần
  }
  client.loop();  // Kiểm tra và xử lý các tin nhắn MQTT

  // Đọc dữ liệu cảm biến
  ldrValue = analogRead(ldrPin);
  float humidity = dht.readHumidity();
  float temperature = dht.readTemperature();

  // Kiểm tra nếu việc đọc cảm biến thất bại
  if (isnan(humidity) || isnan(temperature)) {
    Serial.println("Khong doc duoc du lieu tu cam bien DHT!");
    return;
  }

  
  // Tạo payload JSON
  String payload = "{\"light\":";
  payload += String(ldrValue);
  payload += ",\"humidity\":";
  payload += String(humidity);
  payload += ",\"temperature\":";
  payload += String(temperature);
  payload += "}";

  // Gửi dữ liệu cảm biến đến MQTT
  client.publish("home/sensors", payload.c_str());
  Serial.print("Du lieu da gui: ");
  Serial.println(payload);

  // Độ trễ giữa các lần đọc dữ liệu
  delay(5000);
  sendDataToServer(temperature, humidity, ldrValue);
}
void sendDataToServer(float temp, float hum, int light) {
    if (WiFi.status() == WL_CONNECTED) {
        HTTPClient http;
        http.begin(serverName);
        http.addHeader("Content-Type", "application/x-www-form-urlencoded");

        String postData = "temperature=" + String(temp) + "&humidity=" + String(hum) + "&light=" + String(light);
        int httpResponseCode = http.POST(postData);

        if (httpResponseCode > 0) {
            String response = http.getString();
            Serial.println("Server response: " + response);
        } else {
            Serial.print("Error on sending POST: ");
            Serial.println(httpResponseCode);
        }
        http.end();
    } else {
        Serial.println("WiFi not connected");
    }
}


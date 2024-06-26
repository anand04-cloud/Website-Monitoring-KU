#include <ESP8266WiFi.h>
#include <Adafruit_Sensor.h>
#include <DHT.h>
#include <DHT_U.h>
#include <MQUnifiedsensor.h>
#include <Wire.h>
#include <LiquidCrystal_I2C.h>
#include <ESP8266HTTPClient.h>

// Pin Definitions
#define DHTPIN 2     // GPIO2 corresponds to D4 on NodeMCU
#define DHTTYPE DHT22 // DHT 22 (AM2302)

// Initialize DHT sensor
DHT dht(DHTPIN, DHTTYPE);

// Konfigurasi sensor MQ135
#define placa "ESP8266"
#define Voltage_Resolution 3.3  // Ubah sesuai dengan board Anda
#define pinMQ135 A0
#define typeMQ135 "MQ-135"
#define ADC_Bit_Resolution 10
#define RatioMQ135CleanAir 3.6
MQUnifiedsensor MQ135(placa, Voltage_Resolution, ADC_Bit_Resolution, pinMQ135, typeMQ135);

// LCD Configuration
LiquidCrystal_I2C lcd(0x27, 16, 2); // Address I2C untuk LCD 16x2 bisa berbeda

// WiFi credentials
const char* ssid = "Nursudaryati";
const char* password = "indranagiri";

// Server credentials
const char* serverName = "http://192.168.1.7/Monitoringkualitasudara/kirimdata.php"; // Ganti dengan URL server PHPMyAdmin Anda

WiFiClient client;

void setup() {
  // Start Serial
  Serial.begin(115200);
  
  // Initialize the DHT sensor
  dht.begin();
  
  // Initialize LCD
  lcd.init();
  lcd.backlight();
  
  // Initialize WiFi connection
  WiFi.begin(ssid, password);
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
  Serial.println("");
  Serial.println("WiFi connected");

  // Print to Serial Monitor
  Serial.println("DHT22 and MQ135 Sensor Test");

  // Inisialisasi sensor MQ135 tanpa kalibrasi manual
  MQ135.setRegressionMethod(1);
  MQ135.init();
  MQ135.setR0(10);  // Nilai default, asumsi bahwa sensor sudah dikalibrasi sebelumnya

  // Set parameter regresi untuk CO2
  MQ135.setA(110.47); 
  MQ135.setB(-2.862);

  // Setup LCD display
  lcd.setCursor(0, 0);
  lcd.print("Initializing...");
  delay(2000);
  lcd.clear();
}

void loop() {
  // Reading temperature and humidity from DHT22
  float h = dht.readHumidity();
  float t = dht.readTemperature();
  
  // Pembaruan dan pembacaan data dari MQ135
  MQ135.update();
  float CO2 = MQ135.readSensor();
  
  // Check if any reads failed and exit early (to try again).
  if (isnan(h) || isnan(t)) {
    Serial.println("Failed to read from DHT sensor!");
    lcd.setCursor(0, 0);
    lcd.print("DHT Read Error");
    delay(2000);
    lcd.clear();
    return;
  }

  // Print values to Serial Monitor
  Serial.print("Humidity: ");
  Serial.print(h);
  Serial.print(" %\t");
  Serial.print("Temperature: ");
  Serial.print(t);
  Serial.print(" *C\t");
  Serial.print("CO2: ");
  Serial.print(CO2 + 400); // Menambahkan offset untuk nilai CO2
  Serial.println(" ppm");

  // Display on LCD
  lcd.setCursor(0, 0);
  lcd.print("Temp: ");
  lcd.print(t);
  lcd.print(" C");

  lcd.setCursor(0, 1);
  lcd.print("Humidity: ");
  lcd.print(h);
  lcd.print(" %");

  // Send data to server
  if (WiFi.status() == WL_CONNECTED) {
    HTTPClient http;
    String serverPath = String(serverName) + "?suhu=" + String(t) + "&kelembaban=" + String(h) + "&co2=" + String(CO2 + 400);
    
    http.begin(client, serverPath.c_str()); // Use WiFiClient and URL
    int httpResponseCode = http.GET();
    
    if (httpResponseCode > 0) {
      String response = http.getString();
      Serial.println(httpResponseCode);
      Serial.println(response);
    } else {
      Serial.print("Error on sending data: ");
      Serial.println(httpResponseCode);
    }
    http.end();
  } else {
    Serial.println("WiFi Disconnected");
  }

  delay(2000);
  lcd.clear();
}

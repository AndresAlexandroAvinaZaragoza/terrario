
#include <WiFi.h>
#include <PubSubClient.h>
#include <DHT.h>

// ==========================================
// WIFI
// ==========================================

const char* ssid = "A54 de Angela";
const char* password = "Chimuelo1234";

// ==========================================
// MQTT - MOSQUITTO WINDOWS
// ==========================================

const char* mqtt_server = "10.241.19.84";
const int mqtt_port = 1883;

const char* mqtt_user = "user_12345678";
const char* mqtt_password = "12345678";

const char* topic_sensores =
    "privado/12345678/terrario/sensores";

const char* topic_estado =
    "privado/12345678/terrario/estado";

// ==========================================
// PINES
// ==========================================

#define DHTPIN 27
#define DHTTYPE DHT11
#define PIN_LED 14

// ==========================================
// OBJETOS
// ==========================================

DHT dht(DHTPIN, DHTTYPE);

WiFiClient espClient;
PubSubClient client(espClient);

// ==========================================
// CONTROL DE TIEMPOS
// ==========================================

unsigned long tiempoAnterior = 0;
unsigned long ultimoIntentoMQTT = 0;
unsigned long ultimoIntentoWiFi = 0;
unsigned long ultimoDiagnostico = 0;

const unsigned long intervalo = 5000;

// ==========================================
// VERIFICAR CONEXION TCP CON MOSQUITTO
// ==========================================

void verificarServidorMQTT() {

  if (WiFi.status() != WL_CONNECTED) {
    Serial.println("No hay WiFi para probar MQTT");
    return;
  }

  Serial.println("------------------------");
  Serial.println("Probando acceso a Mosquitto...");

  Serial.print("Servidor: ");
  Serial.println(mqtt_server);

  Serial.print("Puerto: ");
  Serial.println(mqtt_port);

  WiFiClient prueba;

  // Tiempo maximo de espera: 2 segundos
  prueba.setTimeout(2000);

  if (prueba.connect(mqtt_server, mqtt_port, 2000)) {

    Serial.println("TCP OK: Puerto MQTT accesible");
    prueba.stop();

  } else {

    Serial.println("TCP ERROR: No se puede acceder a Mosquitto");

    Serial.println("Revisa:");
    Serial.println("1. IP del servidor");
    Serial.println("2. Firewall de Windows");
    Serial.println("3. Aislamiento de la red WiFi");
  }

  Serial.println("------------------------");
}

// ==========================================
// CONECTAR MQTT
// ==========================================

void conectarMQTT() {

  if (client.connected()) {
    return;
  }

  if (WiFi.status() != WL_CONNECTED) {
    return;
  }

  if (millis() - ultimoIntentoMQTT < 5000) {
    return;
  }

  ultimoIntentoMQTT = millis();

  Serial.println("Conectando a MQTT...");

  if (client.connect(
      "ESP32_Terrario",
      mqtt_user,
      mqtt_password,
      topic_estado,
      0,
      true,
      "offline"
  )) {

    Serial.println("MQTT CONECTADO");

    client.publish(topic_estado, "online", true);

  } else {

    Serial.print("Error MQTT: ");
    Serial.println(client.state());

    // Diagnosticar cuando falle la conexion
    verificarServidorMQTT();
  }
}

// ==========================================
// SETUP
// ==========================================

void setup() {

  Serial.begin(115200);

  pinMode(PIN_LED, OUTPUT);
  digitalWrite(PIN_LED, LOW);

  dht.begin();

  Serial.println();
  Serial.println("========================");
  Serial.println("    BioSphere Control");
  Serial.println("========================");

  WiFi.mode(WIFI_STA);
  WiFi.begin(ssid, password);

  Serial.print("Conectando a WiFi");

  // Esperar maximo 10 segundos
  unsigned long inicio = millis();

  while (
    WiFi.status() != WL_CONNECTED &&
    millis() - inicio < 10000
  ) {

    delay(500);
    Serial.print(".");
  }

  Serial.println();

  if (WiFi.status() == WL_CONNECTED) {

    Serial.println("WIFI CONECTADO");

    Serial.print("IP ESP32: ");
    Serial.println(WiFi.localIP());

    Serial.print("IP Mosquitto: ");
    Serial.println(mqtt_server);

    verificarServidorMQTT();

  } else {

    Serial.println("WIFI DESCONECTADO");
    Serial.println("El control local seguira funcionando");
  }

  client.setServer(mqtt_server, mqtt_port);
  client.setSocketTimeout(2);

  // Permitir primer intento inmediato
  ultimoIntentoMQTT = millis() - 5000;
}

// ==========================================
// LOOP PRINCIPAL
// ==========================================

void loop() {

  // ========================================
  // VERIFICAR WIFI
  // ========================================

  if (WiFi.status() != WL_CONNECTED) {

    if (millis() - ultimoIntentoWiFi >= 10000) {

      ultimoIntentoWiFi = millis();

      Serial.println("WiFi desconectado");
      Serial.println("Intentando reconectar...");

      WiFi.reconnect();
    }

  } else {

    conectarMQTT();
  }

  // ========================================
  // MANTENER MQTT
  // ========================================

  if (client.connected()) {
    client.loop();
  }

  // ========================================
  // DIAGNOSTICO CADA 15 SEGUNDOS
  // ========================================

  if (millis() - ultimoDiagnostico >= 15000) {

    ultimoDiagnostico = millis();

    Serial.println();
    Serial.println("===== ESTADO DE RED =====");

    if (WiFi.status() == WL_CONNECTED) {

      Serial.println("WiFi: CONECTADO");

      Serial.print("IP ESP32: ");
      Serial.println(WiFi.localIP());

      Serial.print("RSSI: ");
      Serial.println(WiFi.RSSI());

    } else {

      Serial.println("WiFi: DESCONECTADO");
    }

    if (client.connected()) {

      Serial.println("MQTT: CONECTADO");

    } else {

      Serial.println("MQTT: DESCONECTADO");
    }

    Serial.println("=========================");
  }

  // ========================================
  // LECTURA DEL DHT11 CADA 5 SEGUNDOS
  // ========================================

  if (millis() - tiempoAnterior >= intervalo) {

    tiempoAnterior = millis();

    float temperatura = dht.readTemperature();
    float humedad = dht.readHumidity();

    if (isnan(temperatura) || isnan(humedad)) {

      Serial.println("ERROR: No se pudo leer DHT11");

      digitalWrite(PIN_LED, LOW);

      return;
    }

    // ======================================
    // CONTROL AUTOMATICO DEL FOCO
    // ======================================

    if (temperatura < 27.0) {

      digitalWrite(PIN_LED, HIGH);

    } else {

      digitalWrite(PIN_LED, LOW);
    }

    int estadoFoco = digitalRead(PIN_LED);

    // ======================================
    // MOSTRAR DATOS EN MONITOR SERIE
    // ======================================

    Serial.println();
    Serial.println("===== DATOS DEL TERRARIO =====");

    Serial.print("Temperatura: ");
    Serial.print(temperatura);
    Serial.println(" C");

    Serial.print("Humedad: ");
    Serial.print(humedad);
    Serial.println(" %");

    Serial.print("Foco: ");
    Serial.println(estadoFoco ? "ON" : "OFF");

    // ======================================
    // CREAR MENSAJE JSON
    // ======================================

    String json = "{";

    json += "\"temperatura\":";
    json += String(temperatura, 1);

    json += ",\"humedad\":";
    json += String(humedad, 1);

    json += ",\"foco\":";
    json += String(estadoFoco);

    json += "}";

    // ======================================
    // ENVIAR DATOS POR MQTT
    // ======================================

    if (client.connected()) {

      bool enviado = client.publish(
          topic_sensores,
          json.c_str()
      );

      if (enviado) {

        Serial.println("MQTT ENVIADO:");
        Serial.println(json);

      } else {

        Serial.println("ERROR: No se pudo publicar MQTT");
      }

    } else {

      Serial.println("Sin MQTT, control local activo");
    }

    Serial.println("==============================");
  }
}

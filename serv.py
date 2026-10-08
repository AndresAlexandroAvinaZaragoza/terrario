from flask import Flask, request, jsonify
import mysql.connector

app = Flask(__name__)

# ======================================================
# CONFIGURACIÓN MYSQL
# ======================================================

db_config = {
    "host": "localhost",
    "user": "root",
    "password": "123456789",
    "database": "terrario"
}

# ======================================================
# RUTA RECIBIR DATOS
# ======================================================

@app.route('/datos', methods=['POST'])
def recibir_datos():

    # ==========================================
    # RECIBIR JSON
    # ==========================================

    data = request.json

    # ==========================================
    # OBTENER DATOS
    # ==========================================

    temp = data.get('temperatura')
    hum = data.get('humedad')
    suelo = data.get('humedad_suelo')
    foco = data.get('foco')

    print("===================================")
    print("DATOS RECIBIDOS DESDE ESP32")
    print("===================================")

    print(f"🌡 Temperatura: {temp} °C")
    print(f"💧 Humedad: {hum} %")
    print(f"🌱 Humedad Suelo: {suelo} %")

    if foco == 1:
        print("💡 Foco: ON")
    else:
        print("💡 Foco: OFF")

    # ==========================================
    # GUARDAR EN MYSQL
    # ==========================================

    try:

        conn = mysql.connector.connect(**db_config)

        cursor = conn.cursor()

        query = """
        INSERT INTO datos
        (temperatura, humedad, humedad_suelo, foco)
        VALUES (%s, %s, %s, %s)
        """

        valores = (
            temp,
            hum,
            suelo,
            foco
        )

        cursor.execute(query, valores)

        conn.commit()

        print("✅ Datos guardados en MySQL")

        cursor.close()
        conn.close()

        return jsonify({
            "status": "ok",
            "mensaje": "Datos guardados"
        })

    except Exception as e:

        print("❌ Error MySQL:")
        print(e)

        return jsonify({
            "status": "error",
            "mensaje": str(e)
        })

# ======================================================
# INICIAR SERVIDOR
# ======================================================

if __name__ == '__main__':

    print("===================================")
    print(" SERVIDOR FLASK INICIADO")
    print("===================================")

    app.run(
        host='0.0.0.0',
        port=5000,
        debug=True
    )
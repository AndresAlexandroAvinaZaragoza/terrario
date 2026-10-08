
<?php

$conexion = new mysqli("localhost", "root", "123456789", "terrario");

$sql = "SELECT * FROM datos ORDER BY id DESC LIMIT 1";

$resultado = $conexion->query($sql);

$fila = $resultado->fetch_assoc();

$temperatura = $fila['temperatura'];
$humedad = $fila['humedad'];
$humedad_suelo = $fila['humedad_suelo'];
$fecha = $fila['fecha'];
$foco = $fila['foco'];

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BioSphere Control</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- CSS -->
    <link rel="stylesheet" href="styles.css">
</head>



<body class="bg-dark text-light">

<div class="d-flex">

    <!-- SIDEBAR -->
    <div class="sidebar p-3 bg-black vh-100">

        <h3 class="text-success">BioSphere</h3>

        <p class="small text-success">
            SISTEMA ONLINE DE CONTROL DE TERRARIO
        </p>

        <ul class="nav flex-column mt-4">

            <li class="nav-item">
                <a class="nav-link active text-success"
                   href="index.php">

                    Panel de Control 

                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link text-light"
                   href="historial.php">

                    Historial

                </a>
            </li>

        </ul>

    </div>

    <!-- CONTENIDO -->
    <div class="content flex-grow-1">

        <!-- HEADER -->
        <nav class="navbar navbar-dark bg-black px-4">

            <span class="navbar-brand">
                BioSphere Control
            </span>

        </nav>

        <!-- DASHBOARD -->
        <div class="container mt-4">

            <div class="row g-4">

                <!-- TEMPERATURA -->
                <div class="col-md-4">

                    <div class="card  text-center p-4">

                        <h5>Temperatura</h5>

                        <h1 class="display-4 text-warning">
                            <?php echo $temperatura; ?>°C
                        </h1>
                        <p>Sensor DHT11</p>

                    </div>

                </div>

                <!-- HUMEDAD -->
                <div class="col-md-4">

                    <div class="card text-center p-4">

                        <h5>Humedad</h5>

                        <h1 class="display-4 text-success">
                            <?php echo $humedad; ?>%
                        </h1>

                        <p>Ambiente</p>

                    </div>

                </div>

                <!-- HUMEDAD SUELO -->
                <div class="col-md-4">

                    <div class="card  text-center p-4">

                        <h5>Humedad Suelo</h5>

                        <h1 class="display-4 text-success">
                            <?php echo $humedad_suelo; ?>%
                        </h1>

                        <small>
                            <?php echo $fecha; ?>
                        </small>

                    </div>

                </div>

                <!-- PERFIL -->
                <div class="col-md-4">

                    <div class="card bg-secondary p-3">

                        <img
                            src="https://images.unsplash.com/photo-1544735716-392fe2489ffa"
                            class="img-fluid rounded mb-3"
                        >

                        <h5>Imagen Reptil</h5>

                        <p>Iguana - 2 años</p>

                    </div>

                </div>

           
                <!-- ESTADO DEL FOCO -->
                <div class="col-md-4">

                    <div class="card  p-4 text-center">

                        <h5>Estado de la lampara</h5>

                        <?php if($foco == 1){ ?>

                            <i class="bi bi-lightbulb-fill bulb-on"></i>

                            <h3 class="text-warning mt-3">
                                ENCENDIDO
                            </h3>

                        <?php } else { ?>

                            <i class="bi bi-lightbulb-fill bulb-off"></i>

                            <h3 class="text-secondary mt-3">
                                APAGADO
                            </h3>

                        <?php } ?>

                    </div>

                </div>
                <!-- RED -->
                <div class="col-md-4">

                    <div class="card bg-secondary p-3 text-center">

                        <h5>Red</h5>

                        <p class="text-success">
                            Estable
                        </p>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

    <!-- JAVASCRIPT -->
    <script>

        async function actualizarDatos() {

            try {

                const respuesta = await fetch("datos.php");

                const datos = await respuesta.json();

                // TEMPERATURA
                document.getElementById("temp").innerHTML =
                    datos.temperatura + "°C";

                // HUMEDAD
                document.getElementById("hum").innerHTML =
                    datos.humedad + "%";

                // HUMEDAD SUELO
                document.getElementById("suelo").innerHTML =
                    datos.humedad_suelo + "%";

                // FECHA
                document.getElementById("fecha").innerHTML =
                    "Última actualización: " + datos.fecha;

            } catch(error) {

                console.log("Error:", error);

            }

        }

        // Ejecutar al abrir
        actualizarDatos();

        // Actualizar cada 3 segundos
        setInterval(actualizarDatos, 3000);

    </script>

    <script>

        setTimeout(() => {

            location.reload();

        }, 1000);

    </script>

</body>
</html>
<?php

$conexion = new mysqli("localhost", "root", "123456789", "terrario");

if ($conexion->connect_error) {
    die("Error de conexión");
}

// OBTENER DATOS MÁS RECIENTES PRIMERO
$sql = "SELECT * FROM datos ORDER BY id DESC";

$resultado = $conexion->query($sql);

?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Historial | BioSphere</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- CSS -->
    <link rel="stylesheet" href="styles.css">

</head>

<body class="bg-dark text-light">

<div class="d-flex">

    <!-- SIDEBAR -->
    <div class="sidebar p-3 bg-black vh-100">

        <h3 class="text-success">
            BioSphere
        </h3>

        <p class="small text-success">
            SISTEMA ONLINE
        </p>

        <ul class="nav flex-column mt-4">

            <li class="nav-item">
                <a class="nav-link text-light"
                   href="index.php">

                    Dashboard

                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link active text-success"
                   href="historial.php">

                    History

                </a>
            </li>

        </ul>

    </div>

    <!-- CONTENIDO -->
    <div class="content flex-grow-1">

        <!-- HEADER -->
        <nav class="navbar navbar-dark bg-black px-4">

            <span class="navbar-brand">
                Historial de Sensores
            </span>

        </nav>

        <!-- TABLA -->
        <div class="container mt-4">

            <div class="card bg-secondary p-4">

                <h3 class="mb-4">
                    Últimos Registros
                </h3>

                <div class="table-responsive">

                    <table class="table table-dark table-hover align-middle">

                        <thead>

                        <tr>

                            <th>ID</th>

                            <th>Temperatura</th>

                            <th>Humedad</th>

                            <th>Humedad Suelo</th>

                            <th>Foco</th>

                            <th>Fecha</th>

                        </tr>

                        </thead>

                        <tbody>

                        <?php while($fila = $resultado->fetch_assoc()) { ?>

                            <tr>

                                <td>
                                    <?php echo $fila['id']; ?>
                                </td>

                                <td class="text-warning">
                                    <?php echo $fila['temperatura']; ?> °C
                                </td>

                                <td class="text-success">
                                    <?php echo $fila['humedad']; ?> %
                                </td>

                                <td class="text-info">
                                    <?php echo $fila['humedad_suelo']; ?> %
                                </td>

                                <td>

                                    <?php if($fila['foco'] == 1){ ?>

                                        <i class="bi bi-lightbulb-fill text-warning"></i>

                                        ON

                                    <?php } else { ?>

                                        <i class="bi bi-lightbulb-fill text-secondary"></i>

                                        OFF

                                    <?php } ?>

                                </td>

                                <td>
                                    <?php echo $fila['fecha']; ?>
                                </td>

                            </tr>

                        <?php } ?>

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>

</div>

</body>
</html>
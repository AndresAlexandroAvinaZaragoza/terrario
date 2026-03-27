<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BioSphere Control</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- CSS propio -->
    <link rel="stylesheet" href="styles.css">
</head>

<body class="bg-dark text-light">

<!-- SIDEBAR -->
<div class="d-flex">

    <div class="sidebar p-3">
        <h3 class="text-success">BioSphere</h3>
        <p class="small text-success">SYSTEM ONLINE</p>

        <ul class="nav flex-column mt-4">
            <li class="nav-item"><a class="nav-link active" href="#">Dashboard</a></li>
            <li class="nav-item"><a class="nav-link" href="#">History</a></li>
            <li class="nav-item"><a class="nav-link" href="#">Controls</a></li>
            <li class="nav-item"><a class="nav-link" href="#">Reptile</a></li>
            <li class="nav-item"><a class="nav-link" href="#">Settings</a></li>
        </ul>
    </div>

    <!-- CONTENIDO -->
    <div class="content flex-grow-1">

        <!-- HEADER -->
        <nav class="navbar navbar-dark bg-black px-4">
            <span class="navbar-brand">BioSphere Control</span>
        </nav>

        <!-- DASHBOARD -->
        <div class="container mt-4">

            <div class="row g-4">

                <!-- TEMPERATURA -->
                <div class="col-md-6">
                    <div class="card card-dark text-center p-4">
                        <h5>Temperatura</h5>
                        <h1 class="display-4 text-warning">28°C</h1>
                        <p>Status: Heating</p>
                    </div>
                </div>

                <!-- HUMEDAD -->
                <div class="col-md-6">
                    <div class="card card-dark text-center p-4">
                        <h5>Humedad</h5>
                        <h1 class="display-4 text-success">65%</h1>
                        <p>Status: Optimal</p>
                    </div>
                </div>

                <!-- PERFIL -->
                <div class="col-md-4">
                    <div class="card card-dark p-3">
                        <img src="" class="img-fluid rounded mb-3">
                        <h5>Imagen Reptil</h5>
                        <p>Iguana - 2 años</p>
                    </div>
                </div>

                <!-- CONTROLES -->
                <div class="col-md-4">
                    <div class="card card-dark p-3">
                        <h5>Controles</h5>
                        <button class="btn btn-warning w-100 mt-2">Heat Lamp</button>
                        <button class="btn btn-secondary w-100 mt-2">Mister</button>
                    </div>
                </div>

                <!-- RED -->
                <div class="col-md-4">
                    <div class="card card-dark p-3 text-center">
                        <h5>Red</h5>
                        <p class="text-success">Estable</p>
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>

</body>
</html>
<?php
session_start();

if (!isset($_SESSION["usuario"])) {
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel - Club Atlético Huracán</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="styles.css" rel="stylesheet">
</head>
<body class="panel-body">

    <nav class="navbar navbar-expand-lg navbar-dark shadow-sm w-100 mb-5" style="background-color: #960f0f; padding: 1rem 2rem;">
        <div class="container-fluid">
            <span class="navbar-brand mb-0 h1">C.A. Huracán - Portal</span>
            <a href="logout.php" class="btn btn-light btn-sm fw-bold">Cerrar Sesión</a>
        </div>
    </nav>

    <div class="container mb-5">
        <div class="row g-4">
            
            <div class="col-md-8">
                <div class="card p-4 h-100 shadow-sm">
                    <div class="card-body">
                        <h1 class="h3 text-danger fw-bold mb-3">¡Bienvenido/a, <?php echo htmlspecialchars($_SESSION["usuario"]); ?>!</h1>
                        <p class="text-secondary">Estás conectado al sistema de gestión oficial del Globo.</p>

                        <?php if (isset($_SESSION["rol"]) && $_SESSION["rol"] === "admin") { ?>
                            <hr>
                            <div class="alert alert-danger" role="alert">
                                <strong>Permisos de Administrador:</strong> Acceso total al sistema habilitado.
                            </div>
                            <a href="admin_usuarios.php" class="btn text-white fw-bold btn-huracan">Ir al Panel de Administración</a>
                        <?php } ?>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card p-3 text-center h-100 shadow-sm">
                    <div class="foto-contenedor text-center mb-3">
                        <img src="img/cancha.webp" alt="Estadio Huracán" class="img-fluid rounded w-100" style="object-fit: contain;">
                    </div>
                    <h5 class="text-danger fw-bold mb-1">Estadio Huracán</h5>
                    <p class="text-danger fw-bold mb-1">El Globito</p>
                    <p class="small text-muted mb-0">"Pasión que no se explica, se siente."</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card p-4 text-center h-100 shadow-sm">
                    <div class="card-body d-flex flex-column justify-content-between">
                        <div>
                            <h3 class="h5 text-danger fw-bold mb-3">Noticias</h3>
                            <p class="text-secondary small">Mantenete informado sobre lo que pasa en el club</p>
                        </div>
                        <a href="https://cahuracan.com/noticias" class="btn btn-huracan btn-sm w-100 fw-bold mt-3">Ver noticia</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card p-4 text-center h-100 shadow-sm">
                    <div class="card-body d-flex flex-column justify-content-between">
                        <div>
                            <h3 class="h5 text-danger fw-bold mb-3">Actividades</h3>
                            <p class="text-secondary small">Descubri todas las disciplinas y deportes que se practican en el club.</p>
                        </div>
                        <a href="https://cahuracan.com/actividades" class="btn btn-huracan btn-sm w-100 fw-bold mt-3">Ver actividades</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card p-4 text-center h-100 shadow-sm">
                    <div class="card-body d-flex flex-column justify-content-between">
                        <div>
                            <h3 class="h5 text-danger fw-bold mb-3">Entradas</h3>
                            <p class="text-secondary small">Adquirí tus ubicaciones y sacá tus entradas para el próximo partido.</p>
                        </div>
                        <a href="https://cahuracan.com/entradas" class="btn btn-huracan btn-sm w-100 fw-bold mt-3">Comprar entradas</a>
                    </div>
                </div>
            </div>

        </div>
    </div>

</body>
</html>
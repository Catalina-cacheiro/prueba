<?php
session_start();
require "conexion.php";

if (!isset($_SESSION["usuario"]) || $_SESSION["rol"] !== "admin") {
    header("Location: panel.php");
    exit;
}

$stmt = $conexionbd->prepare("SELECT * FROM usuarios WHERE fecha_archivado IS NOT NULL");
$stmt->execute();
$archivados = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuarios Archivados</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="styles.css" rel="stylesheet">
</head>
<body class="bg-light">

    <nav class="navbar navbar-dark bg-secondary">
        <div class="container">
            <span class="navbar-brand mb-0 h1">Usuarios Archivados</span>
            <a href="admin_usuarios.php" class="btn btn-light btn-sm">Volver al Panel Admin</a>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="card shadow-sm p-4">
            <h1 class="h4 mb-3">Cuentas Archivadas</h1>

            <div class="table-responsive">
                <table class="table table-striped align-middle">
                    <thead class="table-secondary">
                        <tr>
                            <th>ID</th>
                            <th>Nombre y Apellido</th>
                            <th>DNI</th>
                            <th>Email</th>
                            <th>Fecha de Archivado</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($archivados) > 0) { ?>
                            <?php foreach ($archivados as $u) { ?>
                                <tr>
                                    <td><?php echo (int)$u["id"]; ?></td>
                                    <td><?php echo htmlspecialchars($u["nombre"] . " " . $u["apellido"]); ?></td>
                                    <td><?php echo htmlspecialchars($u["dni"]); ?></td>
                                    <td><?php echo htmlspecialchars($u["email"]); ?></td>
                                    <td><?php echo htmlspecialchars($u["fecha_archivado"]); ?></td>
                                    <td class="text-center">
                                        <form action="reactivar_usuario.php" method="POST" class="d-inline" onsubmit="return confirm('¿Estás seguro de reactivar este usuario?');">
                                            <input type="hidden" name="id" value="<?php echo (int)$u["id"]; ?>">
                                            <button type="submit" class="btn btn-sm btn-success">Reactivar</button>
                                        </form>

                                        <form action="eliminar_usuario.php" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar definitivamente este usuario? Esta acción NO se puede deshacer.');">
                                            <input type="hidden" name="id" value="<?php echo (int)$u["id"]; ?>">
                                            <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php } ?>
                        <?php } else { ?>
                            <tr>
                                <td colspan="6" class="text-center text-muted py-3">No hay usuarios archivados.</td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</body>
</html>
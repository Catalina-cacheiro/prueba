<?php
session_start();
require "conexion.php";

if (!isset($_SESSION["usuario"]) || $_SESSION["rol"] !== "admin") {
    header("Location: panel.php");
    exit;
}

$busqueda = isset($_GET["busqueda"]) ? trim($_GET["busqueda"]) : "";

if ($busqueda !== "") {
    $stmt = $conexionbd->prepare("SELECT * FROM usuarios WHERE fecha_archivado IS NULL AND (nombre LIKE ? OR apellido LIKE ? OR dni LIKE ? OR email LIKE ?)");
    $searchTerm = "%" . $busqueda . "%";
    $stmt->execute([$searchTerm, $searchTerm, $searchTerm, $searchTerm]);
} else {
    $stmt = $conexionbd->prepare("SELECT * FROM usuarios WHERE fecha_archivado IS NULL");
    $stmt->execute();
}
$usuarios = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="styles.css" rel="stylesheet">
</head>
<body class="bg-light">

    <nav class="navbar navbar-dark bg-dark">
        <div class="container">
            <span class="navbar-brand mb-0 h1">Panel Administrativo</span>
            <div>
                <a href="panel.php" class="btn btn-outline-light btn-sm me-2">Mi Panel</a>
                <a href="usuarios_archivados.php" class="btn btn-warning btn-sm me-2">Ver Archivados</a>
                <a href="logout.php" class="btn btn-danger btn-sm">Salir</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="card shadow-sm p-4">
            <h1 class="h4 mb-3">Gestión de Usuarios Activos</h1>

            <form method="GET" class="input-group mb-4">
                <input type="text" name="busqueda" class="form-control" placeholder="Buscar por nombre, apellido, DNI o email..." value="<?php echo htmlspecialchars($busqueda); ?>">
                <button class="btn btn-primary" type="submit">Buscar</button>
                <?php if ($busqueda !== "") { ?>
                    <a href="admin_usuarios.php" class="btn btn-outline-secondary">Ver todos</a>
                <?php } ?>
            </form>

            <div class="table-responsive">
                <table class="table table-striped align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Nombre y Apellido</th>
                            <th>DNI</th>
                            <th>Email</th>
                            <th>Rol</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($usuarios) > 0) { ?>
                            <?php foreach ($usuarios as $u) { ?>
                                <tr>
                                    <td><?php echo (int)$u["id"]; ?></td>
                                    <td><?php echo htmlspecialchars($u["nombre"] . " " . $u["apellido"]); ?></td>
                                    <td><?php echo htmlspecialchars($u["dni"]); ?></td>
                                    <td><?php echo htmlspecialchars($u["email"]); ?></td>
                                    <td>
                                        <span class="badge bg-<?php echo ($u["rol"] === 'admin') ? 'danger' : 'primary'; ?>">
                                            <?php echo htmlspecialchars($u["rol"]); ?>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <a href="modificar_usuario.php?id=<?php echo (int)$u["id"]; ?>" class="btn btn-sm btn-warning">Modificar</a>

                                        <?php if ((int)$u["id"] !== (int)$_SESSION["id"]) { ?>
                                            <form action="archivar_usuario.php" method="POST" class="d-inline" onsubmit="return confirm('¿Estás seguro de archivar este usuario?');">
                                                <input type="hidden" name="id" value="<?php echo (int)$u["id"]; ?>">
                                                <button type="submit" class="btn btn-sm btn-secondary">Archivar</button>
                                            </form>
                                        <?php } ?>
                                    </td>
                                </tr>
                            <?php } ?>
                        <?php } else { ?>
                            <tr>
                                <td colspan="6" class="text-center text-muted py-3">No se encontraron usuarios activos.</td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</body>
</html>
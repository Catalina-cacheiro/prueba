<?php
session_start();
require "conexion.php";

if (!isset($_SESSION["usuario"]) || $_SESSION["rol"] !== "admin") {
    header("Location: panel.php");
    exit;
}

$error = "";
$exito = "";
$id = isset($_GET["id"]) ? (int)$_GET["id"] : 0;

if ($id <= 0) {
    header("Location: admin_usuarios.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre   = trim($_POST["nombre"]);
    $apellido = trim($_POST["apellido"]);
    $dni      = trim($_POST["dni"]);
    $email    = trim($_POST["email"]);
    $rol      = $_POST["rol"];

    if ($nombre === "" || $apellido === "" || $dni === "" || $email === "") {
        $error = "Todos los campos son obligatorios.";
    } elseif (!in_array($rol, ["admin", "usuario"], true)) {
        $error = "Rol inválido.";
    } elseif ($id === (int)$_SESSION["id"] && $rol !== "admin") {
        $error = "No podés quitarte a vos mismo el rol de administrador.";
    } else {
        // Verificar que el DNI o el email no pertenezcan a OTRO usuario
        $busca = $conexionbd->prepare("SELECT id FROM usuarios WHERE (dni = ? OR email = ?) AND id <> ?");
        $busca->execute([$dni, $email, $id]);

        if ($busca->fetch()) {
            $error = "El DNI o el correo electrónico ya pertenecen a otro usuario.";
        } else {
            $stmt = $conexionbd->prepare("UPDATE usuarios SET nombre = ?, apellido = ?, dni = ?, email = ?, rol = ? WHERE id = ?");
            $stmt->execute([$nombre, $apellido, $dni, $email, $rol, $id]);
            $exito = "Usuario actualizado correctamente.";
        }
    }
}

$stmt = $conexionbd->prepare("SELECT * FROM usuarios WHERE id = ?");
$stmt->execute([$id]);
$usuario = $stmt->fetch();

if (!$usuario) {
    header("Location: admin_usuarios.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar Usuario - C.A. Huracán</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="styles.css" rel="stylesheet">
</head>
<body class="bg-light d-flex justify-content-center align-items-center min-vh-100">

    <div class="card shadow-sm" style="width: 400px;">
        <div class="card-body p-4">
            <h1 class="h4 text-center mb-4 text-danger fw-bold">Modificar Usuario</h1>

            <?php if ($error !== "") { ?>
                <div class="alert alert-danger py-2 text-center"><?php echo htmlspecialchars($error); ?></div>
            <?php } ?>

            <?php if ($exito !== "") { ?>
                <div class="alert alert-success py-2 text-center"><?php echo htmlspecialchars($exito); ?></div>
            <?php } ?>

            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Nombre</label>
                    <input type="text" name="nombre" class="form-control" value="<?php echo htmlspecialchars($usuario["nombre"]); ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Apellido</label>
                    <input type="text" name="apellido" class="form-control" value="<?php echo htmlspecialchars($usuario["apellido"]); ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">DNI</label>
                    <input type="text" name="dni" class="form-control" value="<?php echo htmlspecialchars($usuario["dni"]); ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Correo electrónico</label>
                    <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($usuario["email"]); ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Rol</label>
                    <select name="rol" class="form-select">
                        <option value="usuario" <?php echo ($usuario["rol"] === 'usuario') ? 'selected' : ''; ?>>Usuario</option>
                        <option value="admin" <?php echo ($usuario["rol"] === 'admin') ? 'selected' : ''; ?>>Admin</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-huracan w-100">Guardar Cambios</button>
            </form>

            <hr>
            <a href="admin_usuarios.php" class="btn btn-outline-secondary w-100">Volver al panel</a>
        </div>
    </div>

</body>
</html>
<?php
require "conexion.php";

$error = "";
$exito = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre   = trim($_POST["nombre"]);
    $apellido = trim($_POST["apellido"]);
    $dni      = trim($_POST["dni"]);
    $email    = trim($_POST["email"]);
    $clave    = $_POST["clave"];
    $clave2   = $_POST["clave2"];

    if ($nombre === "" || $apellido === "" || $dni === "" || $email === "" || $clave === "" || $clave2 === "") {
        $error = "Completá todos los campos.";
    } elseif ($clave !== $clave2) {
        $error = "Las contraseñas no coinciden.";
    } elseif (strlen($clave) < 8) {
        $error = "La contraseña debe tener al menos 8 caracteres.";
    } else {
        $busca = $conexionbd->prepare("SELECT id FROM usuarios WHERE dni = ? OR email = ?");
        $busca->execute([$dni, $email]);

        if ($busca->fetch()) {
            $error = "El DNI o el correo electrónico ya están registrados.";
        } else {
            $cuenta_usuarios = $conexionbd->query("SELECT COUNT(*) FROM usuarios")->fetchColumn();
            
            $rol = ($cuenta_usuarios == 0) ? 'admin' : 'usuario';

            $hash = password_hash($clave, PASSWORD_DEFAULT);
            $alta = $conexionbd->prepare("INSERT INTO usuarios (nombre, apellido, dni, email, clave, rol) VALUES (?, ?, ?, ?, ?, ?)");
            $alta->execute([$nombre, $apellido, $dni, $email, $hash, $rol]);

            $exito = "Usuario creado correctamente. Tu rol asignado es: " . $rol;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="styles.css" rel="stylesheet">
</head>
<body class="bg-light d-flex justify-content-center align-items-center min-vh-100">

    <div class="card card-login shadow-sm">
        <div class="card-body p-5">
            <h1 class="h3 text-center mb-4 text-danger fw-bold">Crear cuenta</h1>
            <div class="foto-contenedor text-center mb-4">
                <img src="img/globo.png" alt="Logo Huracán">
            </div>

            <?php if ($error !== "") { ?>
                <div class="alert alert-danger py-2 text-center"><?php echo $error; ?></div>
            <?php } ?>

            <?php if ($exito !== "") { ?>
                <div class="alert alert-success py-2 text-center">
                    <?php echo $exito; ?><br><br>
                    <a href="index.php" class="btn btn-sm btn-primary">Iniciar sesión</a>
                </div>
            <?php } ?>

            <form method="POST">
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre</label>
                    <input type="text" name="nombre" id="nombre" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="apellido" class="form-label">Apellido</label>
                    <input type="text" name="apellido" id="apellido" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="dni" class="form-label">DNI</label>
                    <input type="text" name="dni" id="dni" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Correo electrónico</label>
                    <input type="email" name="email" id="email" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="clave" class="form-label">Contraseña</label>
                    <input type="password" name="clave" id="clave" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="clave2" class="form-label">Repetir contraseña</label>
                    <input type="password" name="clave2" id="clave2" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-huracan w-100">Crear cuenta</button>
            </form>

            <hr>
            <a href="index.php" class="btn btn-outline-secondary w-100">Volver al inicio</a>
        </div>
    </div>

</body>
</html>
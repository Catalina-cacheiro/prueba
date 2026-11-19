<?php
session_start();

if (isset($_SESSION["usuario"])) {
    header("Location: panel.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="styles.css" rel="stylesheet">
</head>
<body class="d-flex justify-content-center align-items-center min-vh-100">

    <div class="tarjeta-login">
        <h1>Iniciar sesión</h1>

        <div class="foto-contenedor">
            <img src="img/globo.png" alt="Logo Huracán">
        </div>

        <?php if (isset($_GET["error"])) { ?>
            <div class="alert alert-danger py-2 text-center">
                Correo o contraseña incorrectos
            </div>
        <?php } ?>

        <form action="login.php" method="POST">
            <div class="campo">
                <label for="email">Correo electrónico</label>
                <input type="email" name="email" id="email" required>
            </div>

            <div class="campo">
                <label for="clave">Contraseña</label>
                <input type="password" name="clave" id="clave" required>
            </div>

            <button type="submit" class="btn-huracan">Entrar</button>
        </form>

        <hr>
        <a href="crear_usuario.php" class="btn-secundario">Crear cuenta</a>
    </div>

</body>
</html>
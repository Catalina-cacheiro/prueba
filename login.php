<?php
session_start();
require "conexion.php";

$email = isset($_POST["email"]) ? trim($_POST["email"]) : "";
$clave = isset($_POST["clave"]) ? $_POST["clave"] : "";

$consulta = $conexionbd->prepare("SELECT * FROM usuarios WHERE email = ? AND fecha_archivado IS NULL");
$consulta->execute([$email]);
$fila = $consulta->fetch();

if ($fila && password_verify($clave, $fila["clave"])) {
    session_regenerate_id(true);
    $_SESSION["id"]      = (int)$fila["id"];   // NUEVO: lo necesitan las pantallas de admin
    $_SESSION["usuario"] = $fila["nombre"];
    $_SESSION["rol"]     = $fila["rol"];

    header("Location: panel.php");
    exit;
}

header("Location: index.php?error=1");
exit;
<?php
session_start();
require "conexion.php";

if (!isset($_SESSION["usuario"]) || $_SESSION["rol"] !== "admin") {
    header("Location: panel.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: admin_usuarios.php");
    exit;
}

$id = isset($_POST["id"]) ? (int)$_POST["id"] : 0;

// No permitir que el admin se archive a sí mismo
if ($id > 0 && $id !== (int)$_SESSION["id"]) {
    $stmt = $conexionbd->prepare("UPDATE usuarios SET fecha_archivado = NOW() WHERE id = ?");
    $stmt->execute([$id]);
}

header("Location: admin_usuarios.php");
exit;
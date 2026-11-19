<?php
session_start();
require "conexion.php";

if (!isset($_SESSION["usuario"]) || $_SESSION["rol"] !== "admin") {
    header("Location: panel.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: usuarios_archivados.php");
    exit;
}

$id = isset($_POST["id"]) ? (int)$_POST["id"] : 0;

if ($id > 0) {
    $stmt = $conexionbd->prepare("UPDATE usuarios SET fecha_archivado = NULL WHERE id = ?");
    $stmt->execute([$id]);
}

header("Location: usuarios_archivados.php");
exit;
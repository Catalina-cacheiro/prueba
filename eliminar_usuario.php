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

if ($id > 0 && $id !== (int)$_SESSION["id"]) {
    $stmt = $conexionbd->prepare("DELETE FROM usuarios WHERE id = ? AND fecha_archivado IS NOT NULL");
    $stmt->execute([$id]);
}

header("Location: usuarios_archivados.php");
exit;
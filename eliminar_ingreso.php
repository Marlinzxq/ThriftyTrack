<?php
session_start();
require 'config.php'; // Incluir la conexión a la base de datos

// Verificar que el usuario esté logueado
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Verificar que se haya proporcionado un ID de ingreso
if (!isset($_GET['id'])) {
    header('Location: ingresos.php');
    exit();
}

$id_ingreso = $_GET['id'];

// Eliminar el ingreso
$stmt = $conn->prepare('DELETE FROM ingresos WHERE id_ingreso = ? AND id_usuario = ?');
$stmt->bind_param('ii', $id_ingreso, $_SESSION['user_id']);

if ($stmt->execute()) {
    header("Location: ingresos.php"); // Redirigir a la lista de ingresos después de eliminar
    exit();
} else {
    echo "Error al eliminar el ingreso: " . $conn->error;
}
?>

<?php
include 'config.php';
session_start(); // Asegúrate de que la sesión esté iniciada

// Verificar si el usuario está logueado
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id']; // Obtener el ID del usuario logueado

// Verificar si se ha pasado el ID de la categoría en la URL
if (!isset($_GET['id'])) {
    echo "ID de categoría no especificado.";
    exit;
}

$id_categoria = $_GET['id'];

// Preparar la consulta para seleccionar la categoría del usuario logueado
$query = $conn->prepare('SELECT * FROM categorias WHERE id_categoria = ? AND id_usuario = ?');
$query->bind_param('ii', $id_categoria, $user_id); // 'ii' indica que ambos parámetros son enteros
$query->execute();
$result = $query->get_result();
$categoria = $result->fetch_assoc();

// Verificar si se encontró la categoría y si pertenece al usuario logueado
if (!$categoria) {
    echo "Categoría no encontrada o no tienes permiso para verla.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ver Categoría</title>
    <link rel="stylesheet" href="css/categoria.css">
</head>
<body>
    <h1>Detalles de la Categoría</h1>

    <p><strong>Nombre:</strong> <?= htmlspecialchars($categoria['nombre']) ?></p>
    <p><strong>Tipo:</strong> <?= htmlspecialchars($categoria['tipo']) ?></p>

    <a href="categorias.php" class="btn">Volver a Categorías</a>
</body>
</html>

<?php
session_start();
include 'config.php';

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener el ID del usuario desde la sesión
    $id_usuario = $_SESSION['user_id'];
    $nombre = $_POST['nombre'];
    $tipo = $_POST['tipo'];

    // Insertar la nueva categoría en la base de datos
    $stmt = $conn->prepare('INSERT INTO categorias (id_usuario, nombre, tipo) VALUES (?, ?, ?)');
    $stmt->bind_param('iss', $id_usuario, $nombre, $tipo);
    $stmt->execute();

    header('Location: categorias.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Categoría</title>
    <link rel="stylesheet" href="css/categoria.css">
</head>
<body>
    <h1>Agregar Categoría</h1>
    <form action="agregar_categoria.php" method="POST">
        <label for="nombre">Nombre:</label>
        <input type="text" name="nombre" required>

        <label for="tipo">Tipo:</label>
        <select name="tipo" required>
            <option value="gasto">Gasto</option>
            <option value="ingreso">Ingreso</option>
        </select>

        <button type="submit" class="btn">Agregar</button>
    </form>
</body>
</html>

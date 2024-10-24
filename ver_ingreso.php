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

// Obtener los detalles del ingreso
$stmt = $conn->prepare('SELECT * FROM ingresos WHERE id_ingreso = ?');
$stmt->bind_param('i', $id_ingreso);
$stmt->execute();
$result = $stmt->get_result();
$ingreso = $result->fetch_assoc();

if (!$ingreso) {
    header('Location: ingresos.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ver Ingreso</title>
    <link rel="stylesheet" href="css/ingreso.css">
</head>
<body>
    <div class="container">
        <h1>Detalles del Ingreso</h1>
        <p><strong>Monto:</strong> <?= htmlspecialchars($ingreso['monto']) ?></p>
        <p><strong>Fecha:</strong> <?= htmlspecialchars($ingreso['fecha']) ?></p>
        <p><strong>Descripción:</strong> <?= htmlspecialchars($ingreso['descripcion']) ?></p>
        <p><strong>Categoría:</strong>
            <?php
            // Mostrar el nombre de la categoría correspondiente
            $stmt_categoria = $conn->prepare('SELECT nombre FROM categorias WHERE id_categoria = ?');
            $stmt_categoria->bind_param('i', $ingreso['id_categoria']);
            $stmt_categoria->execute();
            $result_categoria = $stmt_categoria->get_result();
            $categoria = $result_categoria->fetch_assoc();
            echo htmlspecialchars($categoria['nombre']);
            ?>
        </p>

        <div style="margin-top: 20px;">
            <a href="ingresos.php" class="btn">Volver a la Lista de Ingresos</a>
        </div>
    </div>
</body>
</html>

<?php
include 'config.php';
session_start(); // Asegúrate de iniciar la sesión

// Verificar que el usuario esté logueado
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id']; // Obtenemos el ID del usuario desde la sesión

// Consulta para obtener las categorías del usuario logueado
$stmt = $conn->prepare('SELECT * FROM categorias WHERE id_usuario = ?');
$stmt->bind_param('i', $user_id); // Vinculamos el ID del usuario
$stmt->execute();
$result = $stmt->get_result();
$categorias = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Categorías</title>
    <link rel="stylesheet" href="css/categoria.css"> <!-- Enlace al archivo CSS -->
</head>
<body>
    <div class="container">
        <h1>Listado de Categorías</h1>

        <!-- Botón para agregar una nueva categoría -->
        <div class="btn-container">
            <a href="agregar_categoria.php" class="btn agregar-btn">Agregar Nueva Categoría</a>
        </div>

        <!-- Tabla que muestra todas las categorías -->
        <table class="tabla-categorias">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Tipo</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($categorias as $categoria): ?>
                    <tr>
                        <td><?= htmlspecialchars($categoria['nombre']) ?></td>
                        <td><?= htmlspecialchars($categoria['tipo']) ?></td>
                        <td class="acciones">
                            <!-- Botones con íconos para ver, editar, eliminar -->
                            <a href="categoria_ver.php?id=<?= $categoria['id_categoria'] ?>" class="icono ver">👁️ Ver detalles</a>
                            <a href="editar_categoria.php?id=<?= $categoria['id_categoria'] ?>" class="icono editar">✏️ Editar</a>
                            <a href="eliminar_categoria.php?id=<?= $categoria['id_categoria'] ?>" class="icono eliminar" onclick="return confirm('¿Estás seguro de eliminar esta categoría?');">🗑️ Eliminar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Botón para redirigir a panel.php -->
        <div class="btn-container" style="margin-top: 20px;">
            <a href="panel.php" class="btn">Ir al Panel</a>
        </div>
    </div>
</body>
</html>

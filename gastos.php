<?php
session_start(); 
require 'config.php'; // Database connection

// Verify that the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id']; // Get the user ID from the session

// Query to get the expenses of the logged-in user
$stmt = $conn->prepare('SELECT * FROM gastos WHERE id_usuario = ?');
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
$gastos = $result->fetch_all(MYSQLI_ASSOC);

// Query to get the categories of the logged-in user
$stmt_categorias = $conn->prepare('SELECT * FROM categorias WHERE id_usuario = ?');
$stmt_categorias->bind_param('i', $user_id);
$stmt_categorias->execute();
$result_categorias = $stmt_categorias->get_result();
$categorias = $result_categorias->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Gastos</title>
    <link rel="stylesheet" href="css/gasto.css"> <!-- Link to CSS file -->
</head>
<body>
    <div class="container">
        <h1>Lista de Gastos</h1>

        <!-- Button to add a new expense -->
        <div class="btn-container">
            <a href="agregar_gastos.php" class="btn agregar-btn">Agregar Nuevo Gasto</a>
        </div>


        <!-- Table displaying all registered expenses -->
        <table class="tabla-gastos">
            <thead>
                <tr>
                    <th>Monto</th>
                    <th>Fecha</th>
                    <th>Descripción</th>
                    <th>Categoría</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($gastos) > 0): ?>
                    <?php foreach ($gastos as $gasto): ?>
                        <tr>
                            <td><?= htmlspecialchars($gasto['monto']) ?></td>
                            <td><?= htmlspecialchars($gasto['fecha_gasto']) ?></td>
                            <td><?= htmlspecialchars($gasto['descripcion']) ?></td>
                            <td>
                                <?php
                                // Display the corresponding category name
                                $categoria_nombre = '';
                                foreach ($categorias as $categoria) {
                                    if ($categoria['id_categoria'] == $gasto['id_categoria']) {
                                        $categoria_nombre = $categoria['nombre'];
                                        break;
                                    }
                                }
                                echo htmlspecialchars($categoria_nombre);
                                ?>
                            </td>
                            <td class="acciones">
                                <!-- Buttons with icons to view, edit, and delete -->
                                <a href="ver_gastos.php?id=<?= $gasto['id_gasto'] ?>" class="icono ver">👁️ Ver</a>
                                <a href="editar_gastos.php?id=<?= $gasto['id_gasto'] ?>" class="icono editar">✏️ Editar</a>
                                <a href="eliminar_gastos.php?id=<?= $gasto['id_gasto'] ?>" class="icono eliminar" onclick="return confirm('¿Estás seguro de eliminar este gasto?');">🗑️ Eliminar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5">No hay gastos registrados.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Button to go back to the panel -->
        <div class="btn-container" style="margin-top: 20px;">
            <a href="panel.php" class="btn">Volver al Panel</a>
        </div>
    </div>
</body>
</html>

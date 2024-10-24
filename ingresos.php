<?php
session_start();
require 'config.php'; // Incluir la conexión a la base de datos

// Verificar que el usuario esté logueado
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id']; // Obtener el ID del usuario desde la sesión

// Consulta para obtener los ingresos del usuario logueado
$stmt = $conn->prepare('SELECT * FROM ingresos WHERE id_usuario = ?');
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
$ingresos = $result->fetch_all(MYSQLI_ASSOC);

// Consulta para obtener las categorías del usuario logueado
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
    <title>Lista de Ingresos</title>
    <link rel="stylesheet" href="css/ingreso.css"> <!-- Enlace al archivo CSS -->
</head>
<body>
    <div class="container">
        <h1>Lista de Ingresos</h1>

        <!-- Botón para agregar un nuevo ingreso -->
        <div class="btn-container">
            <a href="agregar_ingreso.php" class="btn agregar-btn">Agregar Nuevo Ingreso</a>
        </div>

        <!-- Tabla que muestra todos los ingresos registrados -->
        <table class="tabla-ingresos">
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
                <?php if (count($ingresos) > 0): ?>
                    <?php foreach ($ingresos as $ingreso): ?>
                        <tr>
                            <td><?= htmlspecialchars($ingreso['monto']) ?></td>
                            <td><?= htmlspecialchars($ingreso['fecha']) ?></td>
                            <td><?= htmlspecialchars($ingreso['descripcion']) ?></td>
                            <td>
                                <?php
                                // Mostrar el nombre de la categoría correspondiente
                                $categoria_nombre = '';
                                foreach ($categorias as $categoria) {
                                    if ($categoria['id_categoria'] == $ingreso['id_categoria']) {
                                        $categoria_nombre = $categoria['nombre'];
                                        break;
                                    }
                                }
                                echo htmlspecialchars($categoria_nombre);
                                ?>
                            </td>
                            <td class="acciones">
                                <a href="ver_ingreso.php?id=<?= $ingreso['id_ingreso'] ?>" class="icono ver">👁️ Ver</a>
                                <a href="editar_ingreso.php?id=<?= $ingreso['id_ingreso'] ?>" class="icono editar">✏️ Editar</a>
                                <a href="eliminar_ingreso.php?id=<?= $ingreso['id_ingreso'] ?>" class="icono eliminar" onclick="return confirm('¿Estás seguro de eliminar este ingreso?');">🗑️ Eliminar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5">No hay ingresos registrados.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Botón para volver al panel -->
        <div class="btn-container" style="margin-top: 20px;">
            <a href="panel.php" class="btn">Volver al Panel</a>
        </div>
    </div>
</body>
</html>

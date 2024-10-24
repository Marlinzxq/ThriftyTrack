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

$user_id = $_SESSION['user_id']; // Obtener el ID del usuario desde la sesión
$id_ingreso = $_GET['id'];

// Obtener el ingreso a editar
$stmt = $conn->prepare('SELECT * FROM ingresos WHERE id_ingreso = ? AND id_usuario = ?');
$stmt->bind_param('ii', $id_ingreso, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header('Location: ingresos.php'); // Redirigir si no se encuentra el ingreso
    exit();
}

$ingreso = $result->fetch_assoc();

// Consulta para obtener las categorías del usuario logueado de tipo 'ingreso'
$stmt_categorias = $conn->prepare('SELECT * FROM categorias WHERE id_usuario = ? AND tipo = "ingreso"');
$stmt_categorias->bind_param('i', $user_id);
$stmt_categorias->execute();
$result_categorias = $stmt_categorias->get_result();
$categorias = $result_categorias->fetch_all(MYSQLI_ASSOC);

// Actualizar el ingreso
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $monto = $_POST['monto'];
    $fecha = $_POST['fecha'];
    $descripcion = $_POST['descripcion'];
    $id_categoria = $_POST['id_categoria'];

    $stmt_update = $conn->prepare('UPDATE ingresos SET monto = ?, fecha = ?, descripcion = ?, id_categoria = ? WHERE id_ingreso = ? AND id_usuario = ?');
    $stmt_update->bind_param('sssiii', $monto, $fecha, $descripcion, $id_categoria, $id_ingreso, $user_id);

    if ($stmt_update->execute()) {
        header("Location: ingresos.php"); // Redirigir a la lista de ingresos después de actualizar
        exit();
    } else {
        echo "Error al actualizar el ingreso: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Ingreso</title>
    <link rel="stylesheet" href="css/ingreso.css">
</head>
<body>
    <div class="container">
        <h1>Editar Ingreso</h1>
        <form method="POST">
            <label>Monto:</label>
            <input type="number" name="monto" value="<?= htmlspecialchars($ingreso['monto']) ?>" required>

            <label>Fecha:</label>
            <input type="date" name="fecha" value="<?= htmlspecialchars(substr($ingreso['fecha'], 0, 10)) ?>" required>

            <label>Descripción:</label>
            <textarea name="descripcion" required><?= htmlspecialchars($ingreso['descripcion']) ?></textarea>

            <label>Categoría:</label>
            <select name="id_categoria" required>
                <option value="">Selecciona una categoría</option>
                <?php foreach ($categorias as $categoria): ?>
                    <option value="<?= $categoria['id_categoria'] ?>" <?= ($categoria['id_categoria'] == $ingreso['id_categoria']) ? 'selected' : '' ?>><?= htmlspecialchars($categoria['nombre']) ?></option>
                <?php endforeach; ?>
            </select>

            <button type="submit" class="btn">Actualizar Ingreso</button>
        </form>

        <div style="margin-top: 20px;">
            <a href="ingresos.php" class="btn">Volver a la Lista de Ingresos</a>
        </div>
    </div>
</body>
</html>

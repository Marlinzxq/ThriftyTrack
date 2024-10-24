<?php
session_start();
require 'config.php'; // Incluir la conexión a la base de datos

// Verificar que el usuario esté logueado
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id']; // Obtener el ID del usuario desde la sesión

// Obtener las categorías de tipo 'ingreso'
$stmt_categorias = $conn->prepare('SELECT * FROM categorias WHERE id_usuario = ? AND tipo = "ingreso"');
$stmt_categorias->bind_param('i', $user_id);
$stmt_categorias->execute();
$result_categorias = $stmt_categorias->get_result();
$categorias = $result_categorias->fetch_all(MYSQLI_ASSOC);

// Agregar un nuevo ingreso
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $monto = $_POST['monto'];
    $fecha = $_POST['fecha'];
    $descripcion = $_POST['descripcion'];
    $id_categoria = $_POST['id_categoria'];

    $stmt_insert = $conn->prepare('INSERT INTO ingresos (id_usuario, monto, fecha, descripcion, id_categoria) VALUES (?, ?, ?, ?, ?)');
    $stmt_insert->bind_param('isssi', $user_id, $monto, $fecha, $descripcion, $id_categoria);

    if ($stmt_insert->execute()) {
        header("Location: ingresos.php"); // Redirigir a la lista de ingresos después de agregar
        exit();
    } else {
        echo "Error al agregar el ingreso: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar Ingreso</title>
    <link rel="stylesheet" href="css/ingreso.css">
</head>
<body>
    <div class="container">
        <h1>Agregar Ingreso</h1>
        <form method="POST">
            <label>Monto:</label>
            <input type="number" name="monto" required>

            <label>Fecha:</label>
            <input type="datetime-local" name="fecha" required>

            <label>Descripción:</label>
            <textarea name="descripcion" required></textarea>

            <label>Categoría:</label>
            <select name="id_categoria" required>
                <option value="">Selecciona una categoría</option>
                <?php foreach ($categorias as $categoria): ?>
                    <option value="<?= $categoria['id_categoria'] ?>"><?= htmlspecialchars($categoria['nombre']) ?></option>
                <?php endforeach; ?>
            </select>

            <button type="submit" class="btn">Agregar Ingreso</button>
        </form>

        <div style="margin-top: 20px;">
            <a href="ingresos.php" class="btn">Volver a la Lista de Ingresos</a>
        </div>
    </div>
</body>
</html>

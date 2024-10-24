<?php
include 'config.php';
session_start(); // Asegurarse de que la sesión esté iniciada

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
    echo "Categoría no encontrada o no tienes permiso para editarla.";
    exit;
}

// Verificar si se ha enviado el formulario de edición
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $tipo = $_POST['tipo'];

    // Preparar la consulta para actualizar la categoría
    $stmt = $conn->prepare('UPDATE categorias SET nombre = ?, tipo = ? WHERE id_categoria = ? AND id_usuario = ?');
    $stmt->bind_param('ssii', $nombre, $tipo, $id_categoria, $user_id); // 'ssii' para string, string, entero y entero
    $stmt->execute();

    header('Location: categorias.php'); // Redirigir después de la actualización
    exit; // Asegurarse de que el script se detiene después de la redirección
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Categoría</title>
    <link rel="stylesheet" href="css/categoria.css">
</head>
<body>
    <h1>Editar Categoría</h1>
    <form action="editar_categoria.php?id=<?= $id_categoria ?>" method="POST">
        <label for="nombre">Nombre:</label>
        <input type="text" name="nombre" value="<?= htmlspecialchars($categoria['nombre']) ?>" required>

        <label for="tipo">Tipo:</label>
        <select name="tipo" required>
            <option value="gasto" <?= $categoria['tipo'] == 'gasto' ? 'selected' : '' ?>>Gasto</option>
            <option value="ingreso" <?= $categoria['tipo'] == 'ingreso' ? 'selected' : '' ?>>Ingreso</option>
        </select>

        <button type="submit" class="btn">Guardar Cambios</button>
    </form>
</body>
</html>

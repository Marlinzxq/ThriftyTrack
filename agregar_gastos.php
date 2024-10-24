<?php
session_start();
require 'config.php'; // Include the database connection

// Verify that the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id']; // Get the user ID from the session

// Fetch the categories associated with the logged-in user
$stmt_categories = $conn->prepare("SELECT id_categoria, nombre FROM categorias WHERE id_usuario = ?");
$stmt_categories->bind_param('i', $user_id);
$stmt_categories->execute();
$result_categories = $stmt_categories->get_result();
$categorias = $result_categories->fetch_all(MYSQLI_ASSOC);

// Add a new expense
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'add') {
    $monto = $_POST['monto'];
    $fecha = $_POST['fecha'];
    $descripcion = $_POST['descripcion'];
    $id_categoria = $_POST['categoria'];

    $stmt = $conn->prepare("INSERT INTO gastos (monto, fecha_gasto, descripcion, id_usuario, id_categoria) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("issii", $monto, $fecha, $descripcion, $user_id, $id_categoria);
    
    if ($stmt->execute()) {
        header("Location: gastos.php"); // Redirect to the list of expenses after adding
        exit();
    } else {
        echo "Error al agregar el gasto: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar Gasto</title>
    <link rel="stylesheet" href="css/gastos.css">
</head>
<body>
    <h1>Agregar Nuevo Gasto</h1>
    <form method="POST">
        <input type="hidden" name="action" value="add">
        <label>Monto:</label>
        <input type="number" name="monto" step="0.01" required>
        
        <label>Fecha:</label>
        <input type="datetime-local" name="fecha" required>
        
        <label>Descripción:</label>
        <input type="text" name="descripcion" required>
        
        <label>Categoría:</label>
        <select name="categoria" required>
            <option value="">Seleccionar Categoría</option>
            <?php foreach ($categorias as $categoria): ?>
                <option value="<?= $categoria['id_categoria'] ?>"><?= htmlspecialchars($categoria['nombre']) ?></option>
            <?php endforeach; ?>
        </select>
        
        <input type="submit" value="Registrar" class="btn">
    </form>

    <!-- Button to go back to the expense list -->
    <div style="margin-top: 20px;">
        <a href="gastos.php" class="btn">Volver a la Lista de Gastos</a>
    </div>
</body>
</html>

<?php
session_start();
require 'config.php'; // Incluir la conexión a la base de datos

// Verificar que el usuario esté logueado
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id']; // Obtener el ID del usuario desde la sesión

// Verificar si se recibe un ID de gasto para editar
if (isset($_GET['id'])) {
    $gasto_id = $_GET['id'];

    // Consulta para obtener el gasto específico
    $stmt = $conn->prepare("SELECT * FROM gastos WHERE id_gasto = ? AND id_usuario = ?");
    $stmt->bind_param("ii", $gasto_id, $user_id); // Solo el usuario que creó el gasto puede editarlo
    $stmt->execute();
    $gasto = $stmt->get_result()->fetch_assoc();

    if (!$gasto) {
        echo "Gasto no encontrado o no tienes permisos para editarlo.";
        exit();
    }
}

// Actualizar el gasto
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'update') {
    $gasto_id = $_POST['id_gasto'];
    $monto = $_POST['monto'];
    
    // Solo actualiza la fecha si se proporciona un nuevo valor
    $fecha = !empty($_POST['fecha']) ? $_POST['fecha'] : $gasto['fecha_gasto'];
    $descripcion = $_POST['descripcion'];

    // Consulta para actualizar el gasto
    $update_stmt = $conn->prepare("UPDATE gastos SET monto = ?, fecha_gasto = ?, descripcion = ? WHERE id_gasto = ?");
    $update_stmt->bind_param("issi", $monto, $fecha, $descripcion, $gasto_id);
    $update_stmt->execute();
    header("Location: gastos.php"); // Redirigir a la lista de gastos después de actualizar
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Gasto</title>
    <link rel="stylesheet" href="css/gastos.css"> <!-- Enlace al CSS -->
</head>
<body>
    <div class="container">
        <h1>Editar Gasto</h1>
        <form method="POST">
            <input type="hidden" name="action" value="update">
            <input type="hidden" name="id_gasto" value="<?php echo $gasto['id_gasto']; ?>">

            <label>Monto:</label>
            <input type="number" name="monto" value="<?php echo $gasto['monto']; ?>" required>

            <label>Fecha:</label>
            <input type="date" name="fecha" value="<?php echo $gasto['fecha_gasto']; ?>">

            <label>Descripción:</label>
            <input type="text" name="descripcion" value="<?php echo $gasto['descripcion']; ?>" required><br><br>
            <input type="submit" value="Actualizar" class="btn">
        </form>

        <!-- Botón para volver a la lista -->
        <div style="margin-top: 20px;">
            <a href="gastos.php" class="btn">Volver a la Lista de Gastos</a>
        </div>
    </div>
</body>
</html>

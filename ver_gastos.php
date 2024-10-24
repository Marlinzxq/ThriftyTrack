<?php
session_start();
require 'config.php';

if (isset($_GET['id'])) {
    $gasto_id = $_GET['id'];
    $user_id = $_SESSION['user_id'];

    $gasto_query = $conn->prepare("SELECT * FROM gastos WHERE id_gasto = ? AND id_usuario = ?");
    $gasto_query->bind_param("ii", $gasto_id, $user_id);
    $gasto_query->execute();
    $gasto = $gasto_query->get_result()->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ver Gasto</title>
    <link rel="stylesheet" href="css/ver.css"> <!-- Estilo CSS aquí -->
</head>
<body>
    <h1>Detalles del Gasto</h1>
    <?php if ($gasto): ?>
    
        <p><strong>Monto:</strong> <?php echo $gasto['monto']; ?></p>
        <p><strong>Fecha:</strong> <?php echo $gasto['fecha_gasto']; ?></p>
        <p><strong>Descripción:</strong> <?php echo $gasto['descripcion']; ?></p>
    <?php else: ?>
        <p>No se encontraron detalles para este gasto.</p>
    <?php endif; ?>
    
    <a href="gastos.php">Volver a la lista de gastos</a>
</body>
</html>

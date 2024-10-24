<?php
require 'vendor/autoload.php'; // Asegúrate de que el path sea correcto

use Mpdf\Mpdf;

$mpdf = new Mpdf();

// Conectar a la base de datos
require 'config.php'; // Asegúrate de que config.php está en la ruta correcta y que la conexión se llama $conn

// Verificar la conexión
if (!isset($conn) || $conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Iniciar sesión para obtener el ID del usuario actual
session_start();
if (!isset($_SESSION['user_id'])) {
    die("El usuario no ha iniciado sesión.");
}

$id_usuario = $_SESSION['user_id'];

// Consultar ingresos del usuario
$stmt = $conn->prepare("SELECT * FROM ingresos WHERE id_usuario = ?");
if (!$stmt) {
    die("Error en la preparación de la consulta: " . $conn->error);
}

$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$resultado = $stmt->get_result();

// Verificar si hay resultados
if ($resultado->num_rows === 0) {
    die("No se encontraron ingresos para este usuario.");
}

$html = '
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Ingresos</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { padding: 10px; border: 1px solid #000; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>Reporte de Ingresos</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Monto</th>
                <th>Fecha</th>
                <th>Descripción</th>
            </tr>
        </thead>
        <tbody>
';

while ($fila = $resultado->fetch_assoc()) {
    $fecha_formateada = date("d/m/Y H:i", strtotime($fila['fecha_ingreso']));
    $html .= '
        <tr>
            <td>' . htmlspecialchars($fila['id_ingreso']) . '</td>
            <td>' . htmlspecialchars($fila['monto']) . '</td>
            <td>' . htmlspecialchars($fecha_formateada) . '</td>
            <td>' . htmlspecialchars($fila['descripcion']) . '</td>
        </tr>
    ';
}

$html .= '
        </tbody>
    </table>
</body>
</html>
';

$mpdf->WriteHTML($html);
$mpdf->Output('reporte_ingresos.pdf', 'D');
?>

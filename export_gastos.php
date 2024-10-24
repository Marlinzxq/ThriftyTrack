<?php
require 'vendor/autoload.php'; // Asegúrate de que el path sea correcto

use Mpdf\Mpdf;

$mpdf = new Mpdf();

// Conectar a la base de datos
require 'config.php';

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener el ID del usuario actual
session_start(); // Asegúrate de que la sesión esté iniciada
$id_usuario = $_SESSION['user_id']; // Obtén el ID del usuario de la sesión

// Consultar gastos
$stmt = $conn->prepare("SELECT * FROM gastos WHERE id_usuario = ?");
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$resultado = $stmt->get_result();

$html = '
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Gastos</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { padding: 10px; border: 1px solid #000; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>Reporte de Gastos</h1>
    <table>
        <thead>
            <tr>
                <th>Monto</th>
                <th>Fecha</th>
                <th>Descripción</th>
            </tr>
        </thead>
        <tbody>
';

// Agregar cada gasto a la tabla
while ($fila = $resultado->fetch_assoc()) {
    $html .= '
        <tr>
            <td>' . $fila['monto'] . '</td>
            <td>' . $fila['fecha_gasto'] . '</td>
            <td>' . $fila['descripcion'] . '</td>
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
$mpdf->Output('reporte_gastos.pdf', 'D'); // 'D' fuerza la descarga del archivo
?>

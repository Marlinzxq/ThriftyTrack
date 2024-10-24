<?php
// Datos de conexión
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "thriftytrackbd";  // Ajustado el nombre de la base de datos

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Error en la conexión: " . $conn->connect_error);
}
?>

<?php
include 'config.php';

// Obtener el ID de la categoría desde la URL
$id_categoria = $_GET['id'];

// Preparar la consulta para eliminar la categoría
$stmt = $conn->prepare('DELETE FROM categorias WHERE id_categoria = ?');
$stmt->bind_param('i', $id_categoria); // 'i' indica que el parámetro es un entero
$stmt->execute();

// Redirigir a la página de categorías después de eliminar
header("Location: categorias.php");
exit; // Asegurarse de que el script se detiene después de la redirección


// $stmt = $conn->prepare("DELETE FROM categorias WHERE id_categoria = ?");
// $stmt->bind_param("i", $id_categoria);
// $stmt->execute();


?>


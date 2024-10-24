<?php
session_start();
require 'config.php';

if (isset($_GET['id'])) {
    $gasto_id = $_GET['id'];
    $user_id = $_SESSION['user_id'];

    $delete_stmt = $conn->prepare("DELETE FROM gastos WHERE id_gasto = ? AND id_usuario = ?");
    $delete_stmt->bind_param("ii", $gasto_id, $user_id);
    $delete_stmt->execute();
}

header("Location: gastos.php?action=delete");
exit();
?>

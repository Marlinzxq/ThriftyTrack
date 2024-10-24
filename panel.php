<?php
session_start(); // Iniciar sesión para acceder a las variables de sesión

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['user_id'])) { // Cambia 'user_id' a 'user_id'
    header("Location: login.php"); // Redirigir a login si no ha iniciado sesión
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración</title>
    <link rel="stylesheet" href="css/panel.css">
</head>
<body>
    <div class="sidebar">
        <div class="profile">
            <img src="img/perfil.jpg" alt="Perfil">
            <h3><?php echo $_SESSION['user_nombre']; ?></h3> <!-- Cambia 'user_nombre' según tu sistema -->
        </div>
        <ul>
            <li><a href="perfil.php">Perfil</a></li>
            <li><a href="gastos.php">Gastos</a></li>
            <li><a href="categorias.php">Categorías</a></li>
            <li><a href="ingresos.php">Ingresos</a></li>
            <li><a href="logout.php">Cerrar Sesión</a></li> <!-- Enlace para cerrar sesión -->
        </ul>
    </div>
    <div class="main-content">
        <header>
            <h2>Panel de Administración</h2>
            <p>Bienvenid@, <?php echo $_SESSION['user_nombre']; ?></p> <!-- Cambia 'user_nombre' según tu sistema -->
        </header>
        <section class="dashboard-info">
            <div class="box">
                <h3>¿Quieres descargar tus ingresos en PDF?</h3>
                <a href="export_ingresos.php" class="btn">Descargar Ingresos</a> <!-- Enlace para descargar ingresos -->
            </div>
            <div class="box">
                <h3>¿Quieres descargar tus gastos en PDF?</h3>
                <a href="export_gastos.php" class="btn">Descargar Gastos</a> <!-- Enlace para descargar gastos -->
            </div>
        </section>
    </div>
</body>
</html>

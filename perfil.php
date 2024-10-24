<?php
session_start(); // Iniciar la sesión

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirigir al login si no está logueado
    exit();
}

// Incluir el archivo de configuración
require 'config.php';

// Obtener el ID del usuario desde la sesión
$user_id = $_SESSION['user_id'];

// Consultar los datos del usuario
$sql = "SELECT * FROM usuario WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Obtener los datos del usuario
    $row = $result->fetch_assoc();
    $nombre = $row['nombre'];
    $apellido = $row['apellido'];
    $telefono = $row['telefono'];
    $correo = $row['correo'];
} else {
    echo "No se encontraron datos del usuario.";
    exit();
}

// Inicializar mensaje de éxito
$mensaje_actualizado = "";

// Verificar si el formulario ha sido enviado para actualizar los datos
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $correo = $_POST['correo'];
    $telefono = $_POST['telefono'];

    // Manejar la contraseña: si se proporciona una nueva, actualizarla
    if (!empty($_POST['contraseña'])) {
        $contraseña = password_hash($_POST['contraseña'], PASSWORD_DEFAULT);
        $sql_update = "UPDATE usuario SET nombre=?, apellido=?, correo=?, contraseña=?, telefono=? WHERE id=?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("sssssi", $nombre, $apellido, $correo, $contraseña, $telefono, $user_id);
    } else {
        // Si no se proporciona contraseña nueva, no actualizar ese campo
        $sql_update = "UPDATE usuario SET nombre=?, apellido=?, correo=?, telefono=? WHERE id=?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("ssssi", $nombre, $apellido, $correo, $telefono, $user_id);
    }

    // Ejecutar la consulta de actualización
    if ($stmt_update->execute()) {
        $mensaje_actualizado = "Perfil actualizado correctamente.";
        // Refrescar los datos del usuario
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
    } else {
        echo "Error al actualizar el perfil: " . $stmt_update->error;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil de Usuario</title>
    <link rel="stylesheet" href="css/perfil.css"> <!-- Asegúrate de tener tu archivo de estilos -->
</head>
<body>
    <div class="sidebar">
        <div class="profile">
            <img src="img/perfil.jpg" alt="Avatar">
            <h3><?php echo htmlspecialchars($nombre) . " ".htmlspecialchars($apellido); ?></h3>
        </div>

        
        <ul>
            <li><a href="panel.php">Panel</a></li>
            <li><a href="gastos.php">Gastos</a></li>
            <li><a href="categorias.php">Categorías</a></li>
            <li><a href="ingresos.php">Ingresos</a></li>
            <li><a href="logout.php">Cerrar Sesión</a></li>
        </ul>
    </div>

    <div class="main-content">
        <h1>Perfil de Usuario</h1>

        <!-- Mostrar el mensaje de actualización -->
        <?php if (!empty($mensaje_actualizado)): ?>
            <div class="alerta-exito">
                <?php echo htmlspecialchars($mensaje_actualizado); ?>
            </div>
        <?php endif; ?>

        <form action="perfil.php" method="POST">
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($nombre); ?>" required>

            <label for="apellido">Apellido:</label>
            <input type="text" id="apellido" name="apellido" value="<?php echo htmlspecialchars($apellido); ?>" required>

            <label for="telefono">Teléfono:</label>
            <input type="text" id="telefono" name="telefono" value="<?php echo htmlspecialchars($telefono); ?>" required>

            <label for="correo">Correo:</label>
            <input type="email" id="correo" name="correo" value="<?php echo htmlspecialchars($correo); ?>" required>

            <label for="contraseña">Nueva Contraseña (opcional):</label>
            <input type="password" id="contraseña" name="contraseña" placeholder="Dejar en blanco si no desea cambiar"><br>
            <br>

            <button type="submit" name="actualizar" class="btn">Actualizar Datos</button>
        </form>
    </div>

    <style>
        /* Estilos para la alerta de éxito */
        .alerta-exito {
            color: #155724;
            background-color: #d4edda;
            border-color: #c3e6cb;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid transparent;
            border-radius: 4px;
        }

        .main-content {
            padding: 20px;
        }

        .sidebar {
            width: 200px;
            float: left;
            background-color: #f4f4f4;
            padding: 20px;
        }

        .profile img {
            width: 100px;
            border-radius: 50%;
        }

        .profile h2 {
            text-align: center;
        }

        .sidebar ul {
            list-style: none;
            padding: 0;
        }

        .sidebar ul li {
            margin: 15px 0;
        }

        .sidebar ul li a {
            text-decoration: none;
            color: #333;
        }

        .btn {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
        }

        .btn:hover {
            background-color: #0056b3;
        }
    </style>
</body>
</html>

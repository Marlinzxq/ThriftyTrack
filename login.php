<?php
// Mostrar errores para la depuración
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start(); // Iniciar la sesión

// Incluir el archivo de configuración de la base de datos
require 'config.php';

// Verificar si se envió el formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $correo = $_POST['correo'];
    $contraseña = $_POST['contraseña'];

    if (empty($correo) || empty($contraseña)) {
        $mensaje_error = "Por favor, complete ambos campos.";
    } else {
        // Preparar la consulta para buscar el usuario en la base de datos
        $query = $conn->prepare("SELECT * FROM usuario WHERE correo = ?");
        if (!$query) {
            $mensaje_error = "Error en la preparación de la consulta: " . $conn->error;
        } else {
            $query->bind_param('s', $correo);
            if ($query->execute()) {
                $result = $query->get_result();
                $usuario = $result->fetch_assoc();
                if ($usuario) {
                    if (password_verify($contraseña, $usuario['contraseña'])) {
                        $_SESSION['user_id'] = $usuario['id'];
                        $_SESSION['user_nombre'] = $usuario['nombre'];
                        header("Location: panel.php");
                        exit();
                    } else {
                        $mensaje_error = "Contraseña incorrecta.";
                    }
                } else {
                    $mensaje_error = "El correo no está registrado.";
                }
            } else {
                $mensaje_error = "Error en la ejecución de la consulta: " . $conn->error;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de Sesión - ThriftyTrack</title>
    <link rel="stylesheet" href="css/login.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <!-- Encabezado con barra de navegación -->
    <header class="bg-dark text-white p-3">
        <div class="container d-flex justify-content-between align-items-center">
            <div class="logo d-flex align-items-center">
                <img src="img/logo.png" alt="Logo de la Empresa" style="width: 40px;">
                <h1 class="ms-2">ThriftyTrack</h1>
            </div>
            <nav>
                <ul class="nav">
                    <li class="nav-item"><a class="nav-link text-white" href="index.html">Inicio</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="sobre_nosotros.html">Sobre Nosotros</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="servicios.html">Servicios</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="contacto.html">Contacto</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="documentacion.html">Documentación</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Sección de inicio de sesión -->
    <section class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h5 class="text-center">Formulario de Inicio de Sesión</h5>
                <?php
                // Mostrar el mensaje de error si existe
                if (isset($mensaje_error)) {
                    echo "<p class='text-danger'>$mensaje_error</p>";
                }
                ?>
                <form action="login.php" method="post">
                    <div class="form-group mb-3">
                        <input class="form-control" type="text" name="correo" value="<?php echo isset($_POST['correo']) ? htmlspecialchars($_POST['correo']) : ''; ?>" placeholder="Correo electrónico" required>
                    </div>
                    <div class="form-group mb-3">
                        <input class="form-control" type="password" name="contraseña" placeholder="Contraseña" required>
                    </div>
                    <div class="d-grid">
                        <input class="btn btn-primary" type="submit" value="Ingresar">
                    </div>
                </form>
                <p class="text-center mt-3">¿No tienes una cuenta? <a href="registro.html">Regístrate</a></p>
            </div>
        </div>
    </section>

    <!-- Pie de página -->
    <footer class="bg-dark text-white py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5>Finanzas</h5>
                    <p>Gestiona tus gastos con ThriftyTrack.</p>
                </div>
                <div class="col-md-4">
                    <h5>Asesoría</h5>
                    <p><i class="bi bi-telephone"></i> +34 912 345 678</p>
                    <p><i class="bi bi-envelope"></i> contacto@thriftytrack.com</p>
                </div>
                <div class="col-md-4">
                    <h5>Suscríbete a nuestro boletín</h5>
                    <form action="#" method="post">
                        <div class="input-group">
                            <input type="email" class="form-control" placeholder="Correo electrónico" required>
                            <button type="submit" class="btn btn-primary">Suscribirse</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="text-center mt-3">
                <p>&copy; 2024 ThriftyTrack. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>

    <!-- Scripts de Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

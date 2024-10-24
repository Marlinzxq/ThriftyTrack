<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de Sesión - ThriftyTrack</title>
    <link rel="stylesheet" href="css/login.css"> <!-- Enlace a tu archivo CSS personalizado -->
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

    <!-- Sección de formulario de inicio de sesión -->
    <section class="form-container my-5">
        <div class="form-login">
            <h1>Iniciar Sesión</h1>
            <!-- Formulario de inicio de sesión -->
            <form action="login.php" method="post">
                <input class="controls" type="text" name="correo" placeholder="Correo electrónico" required>
                <input class="controls" type="password" name="contraseña" placeholder="Contraseña" required>
                
                <input class="buttons btn btn-primary" type="submit" value="Ingresar">
            </form>
            <p><a href="registro.html">¿No tienes una cuenta? Regístrate</a></p>
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

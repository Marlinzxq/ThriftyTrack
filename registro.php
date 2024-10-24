<?php  
// Incluir el archivo de configuración solo una vez
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener los datos enviados desde el formulario
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $correo = $_POST['correo'];
    $contraseña = $_POST['contraseña'];

    // Validar la contraseña
    if (!preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{9,}$/', $contraseña)) {
        echo "<script>
                alert('La contraseña debe tener al menos 9 caracteres, incluyendo al menos una letra mayúscula, una letra minúscula, un número y un carácter especial.');
                window.location.href = 'registro.html';
              </script>";
        exit(); // Detener la ejecución del script
    } else {
        // Hashear la contraseña
        $contraseña_hashed = password_hash($contraseña, PASSWORD_DEFAULT);
        
        // Validar campos opcionales
        $telefono = !empty($_POST['telefono']) ? $_POST['telefono'] : null;

        // Verificar si la conexión a la base de datos es exitosa
        if ($conn === false) {
            die("Error en la conexión con la base de datos.");
        }

        // Validar que el correo no exista
        $query = $conn->prepare("SELECT * FROM usuario WHERE correo = ?");
        if ($query === false) {
            die("Error en la consulta: " . $conn->error);
        }

        $query->bind_param('s', $correo);
        $query->execute();
        $result = $query->get_result();
        
        if ($result->num_rows > 0) {
            echo "<script>alert('El correo ya está registrado.');</script>";
        } else {
            // Insertar el usuario en la base de datos
            $sql = "INSERT INTO usuario (nombre, apellido, correo, contraseña, telefono) 
                    VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            
            if ($stmt === false) {
                die("Error en la consulta: " . $conn->error);
            }
            
            $stmt->bind_param('sssss', $nombre, $apellido, $correo, $contraseña_hashed, $telefono);
            
            if ($stmt->execute()) {
                // Mostrar la alerta y redirigir al usuario a login.php
                echo "<script>
                        alert('Registro exitoso.');
                        window.location.href = 'login.php';
                      </script>";
            } else {
                echo "<script>alert('Error al registrar el usuario.');</script>";
            }
        }
    }
}
?>

<?php
include('conexion.php');
session_start();

// Verificar si el formulario fue enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correo = $_POST['correo'];
    $password = $_POST['password'];

    // Preparar la consulta para obtener los datos del usuario
    $query = "SELECT * FROM usuarios WHERE correo = ?";
    
    // Preparar la declaración
    if ($stmt = $conn->prepare($query)) {
        // Vincular el parámetro
        $stmt->bind_param("s", $correo);

        // Ejecutar la consulta
        $stmt->execute();
        $result = $stmt->get_result();

        // Verificar si el usuario existe
        if ($result->num_rows > 0) {
            $usuario = $result->fetch_assoc();
            
            // Verificar la contraseña
            if (password_verify($password, $usuario['contraseña'])) {
                // Almacenar los datos del usuario en la sesión
                $_SESSION['user_id'] = $usuario['id'];
                $_SESSION['rol_id'] = $usuario['rol_id'];

                // Redirigir al panel correspondiente según el rol
                if ($_SESSION['rol_id'] == 1) {
                    // Redirigir al panel de administrador
                    header("Location: dashboard_admin.php");
                } elseif ($_SESSION['rol_id'] == 2) {
                    // Redirigir al panel de docente
                    header("Location: dashboard_docente.php");
                }
                exit;
            } else {
                echo "Contraseña incorrecta";
            }
        } else {
            echo "No se encontró un usuario con ese correo";
        }

        // Cerrar la declaración
        $stmt->close();
    } else {
        echo "Error en la preparación de la consulta: " . $conn->error;
    }
}

$conn->close();
?>

<!-- Formulario HTML de inicio de sesión -->
<form action="login.php" method="POST">
    <input type="email" name="correo" placeholder="Correo" required>
    <input type="password" name="password" placeholder="Contraseña" required>
    <button type="submit">Iniciar sesión</button>
</form>

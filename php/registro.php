<?php
include('conexion.php');

// Verificar si el formulario fue enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Encriptar la contraseña
    $rol_id = 2; // Asumimos que el rol es docente (ID 2)

    // Preparar la consulta para insertar el nuevo usuario
    $query = "INSERT INTO usuarios (nombre, correo, contraseña, rol_id) VALUES (?, ?, ?, ?)";
    
    // Preparar la declaración
    if ($stmt = $conn->prepare($query)) {
        // Vincular los parámetros
        $stmt->bind_param("sssi", $nombre, $correo, $password, $rol_id);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            echo "Usuario registrado exitosamente";
        } else {
            echo "Error al registrar el usuario: " . $stmt->error;
        }

        // Cerrar la declaración
        $stmt->close();
    } else {
        echo "Error en la preparación de la consulta: " . $conn->error;
    }
}

$conn->close();
?>

<!-- Formulario HTML de registro -->
<form action="registro.php" method="POST">
    <input type="text" name="nombre" placeholder="Nombre" required>
    <input type="email" name="correo" placeholder="Correo" required>
    <input type="password" name="password" placeholder="Contraseña" required>
    <button type="submit">Registrarse</button>
</form>

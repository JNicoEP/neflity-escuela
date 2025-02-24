<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../index.html"); // Redirige si no está logueado
    exit();
}

include 'conexion.php'; // Incluye la conexión a la base de datos

$usuario_id = $_SESSION['usuario_id'];

// Obtener información del usuario desde la base de datos
$sql = "SELECT nombre, email FROM usuarios WHERE id = ?";
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    die(json_encode(["status" => "error", "message" => "Error en la consulta SQL."]));
}

$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $stmt->bind_result($nombre, $email);
    $stmt->fetch();

    echo "<h1>Perfil de Usuario</h1>";
    echo "<p>Nombre: " . htmlspecialchars($nombre) . "</p>";
    echo "<p>Email: " . htmlspecialchars($email) . "</p>";
    echo "<a href='cerrar_sesion.php'>Cerrar sesión</a>";

} else {
    echo "Usuario no encontrado.";
}

$stmt->close();
$conn->close();
?>
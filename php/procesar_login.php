<?php
session_start();
include "conexion.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correo = $_POST['correo'];
    $contrasena = md5($_POST['contrasena']); // Encriptación simple

    $sql = "SELECT id, nombre FROM usuarios WHERE correo = '$correo' AND contrasena = '$contrasena'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $usuario = $result->fetch_assoc();
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['usuario_nombre'] = $usuario['nombre'];
        header("Location: dashboard.php");
    } else {
        $_SESSION['error'] = "Correo o contraseña incorrectos";
        header("Location: login.php");
    }
    exit();
}
?>

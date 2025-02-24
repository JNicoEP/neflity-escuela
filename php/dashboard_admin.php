<?php
session_start();

// Verificar si el usuario está logueado y tiene el rol de admin
if (!isset($_SESSION['user_id']) || $_SESSION['rol_id'] != 1) {
    // Redirigir a la página de inicio de sesión si no es admin
    header("Location: login.php");
    exit;
}

// Aquí iría el contenido del panel de administración
echo "Bienvenido, Administrador.";
?>

<!-- Aquí puedes agregar más opciones y funcionalidad para administrar la plataforma -->

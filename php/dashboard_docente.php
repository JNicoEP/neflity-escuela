<?php
session_start();

// Verificar si el usuario está logueado y tiene el rol de docente
if (!isset($_SESSION['user_id']) || $_SESSION['rol_id'] != 2) {
    // Redirigir a la página de inicio de sesión si no es docente
    header("Location: login.php");
    exit;
}

// Aquí iría el contenido del panel docente
echo "Bienvenido, Docente.";
?>

<!-- Aquí puedes agregar más opciones y funcionalidad para que el docente gestione su material -->

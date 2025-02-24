<?php
session_start();
include('conexion.php');

// Verificar si el usuario está logueado y es admin
if (!isset($_SESSION['user_id']) || $_SESSION['rol_id'] != 1) {
    header("Location: login.php");
    exit;
}

// Verificar si se ha enviado un ID de material a eliminar
if (isset($_GET['id'])) {
    $material_id = $_GET['id'];

    // Eliminar el material
    $query = "DELETE FROM materiales WHERE id = ?";
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("i", $material_id);
        if ($stmt->execute()) {
            echo "Material eliminado exitosamente";
        } else {
            echo "Error al eliminar el material";
        }
        $stmt->close();
    }
}

$conn->close();
?>

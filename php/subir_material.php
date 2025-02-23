<?php
session_start();
include('conexion.php');

// Verificar si el usuario está logueado y tiene el rol adecuado
if (!isset($_SESSION['user_id']) || ($_SESSION['rol_id'] != 1 && $_SESSION['rol_id'] != 2)) {
    header("Location: login.php");
    exit;
}

// Procesar la subida del archivo
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['material'])) {
    $nombre_archivo = $_FILES['material']['name'];
    $tipo_archivo = $_FILES['material']['type'];
    $temporal_archivo = $_FILES['material']['tmp_name'];
    $ruta_destino = "uploads/" . basename($nombre_archivo); // Ruta donde se almacenará el archivo


    // Mover el archivo a la carpeta 'uploads'
    if (move_uploaded_file($temporal_archivo, $ruta_destino)) {
        // Insertar la información del archivo en la base de datos
        $usuario_id = $_SESSION['user_id']; // El ID del usuario que sube el archivo

        $query = "INSERT INTO materiales (nombre_archivo, tipo_archivo, enlace, usuario_id) 
                  VALUES (?, ?, ?, ?)";

        if ($stmt = $conn->prepare($query)) {
            $stmt->bind_param("sssi", $nombre_archivo, $tipo_archivo, $ruta_destino, $usuario_id);

            if ($stmt->execute()) {
                header("Location: pagina_de_exito.php?mensaje=Material subido exitosamente");
            } else {
                header("Location: pagina_de_error.php?mensaje=Error al subir el material");
            }

            $stmt->close();
        }
    } else {
        echo "Error al mover el archivo al servidor.";
    }
}

$conn->close();
?>

<!-- Formulario para subir material -->
<form action="subir_material.php" method="POST" enctype="multipart/form-data">
    <input type="file" name="material" required>
    <button type="submit">Subir material</button>
</form>

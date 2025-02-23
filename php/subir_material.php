<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include 'conexion.php';

if (isset($_SESSION['user_id'])) {
    $usuario_id = $_SESSION['user_id'];
    $tipo_archivo = $_FILES["material"]["type"];
    $temporal_archivo = $_FILES["material"]["tmp_name"];

    // Validación del tamaño del archivo (5MB límite)
    if ($_FILES["material"]["size"] > 5000000) {
        header("Location: ../pagina_de_error.php?mensaje=El archivo es demasiado grande.");
        exit;
    }

    // Validación de la extensión del archivo
    $permitidos = ['pdf', 'docx', 'xlsx', 'pptx', 'jpg', 'jpeg', 'png'];
    $ext = pathinfo($nombre_archivo, PATHINFO_EXTENSION);

    if (!in_array(strtolower($ext), $permitidos)) {
        header("Location: ../pagina_de_error.php?mensaje=Tipo de archivo no permitido.");
        exit;
    }

    // Generar ruta de destino única
    $ruta_destino = "uploads/" . uniqid() . "_" . $nombre_archivo;

    // Mover el archivo a la carpeta 'uploads'
    if (move_uploaded_file($temporal_archivo, $ruta_destino)) {
        // Insertar la información del archivo en la base de datos
        $usuario_id = $_SESSION['user_id'];

        $query = "INSERT INTO materiales (nombre_archivo, tipo_archivo, enlace, usuario_id, fecha_subida) VALUES (?, ?, ?, ?, NOW())";

        if ($stmt = $conn->prepare($query)) {
            $stmt->bind_param("sssi", $nombre_archivo, $tipo_archivo, $ruta_destino, $usuario_id);

            if ($stmt->execute()) {
                header("Location: ../pagina_de_exito.php?mensaje=Material subido exitosamente");
            } else {
                header("Location: ../pagina_de_error.php?mensaje=Error al subir el material");
            }

            $stmt->close();
        }
    } else {
        header("Location: ../pagina_de_error.php?mensaje=Error al mover el archivo al servidor.");
    }
} else {
    header("Location: ../pagina_de_error.php?mensaje=Usuario no autenticado.");
    exit;
}

$conn->close();
?>

<!-- Formulario para subir material -->
<form action="subir_material.php" method="POST" enctype="multipart/form-data">
    <input type="file" name="material" required>
    <button type="submit">Subir material</button>
</form>
 
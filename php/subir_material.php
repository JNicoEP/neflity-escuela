
//Archivos permitidos
    $permitidos = ['pdf', 'docx', 'xlsx', 'pptx', 'jpg', 'jpeg', 'png'];
        $ext = pathinfo($nombre_archivo, PATHINFO_EXTENSION);

        if (!in_array(strtolower($ext), $permitidos)) {
         echo "Tipo de archivo no permitido.";
         exit;
        }

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
 
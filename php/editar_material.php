<?php
session_start();
include('conexion.php');

// Verificar si el usuario está logueado y es admin
if (!isset($_SESSION['user_id']) || $_SESSION['rol_id'] != 1) {
    header("Location: login.php");
    exit;
}

// Verificar si se ha enviado un ID de material a editar
if (isset($_GET['id'])) {
    $material_id = $_GET['id'];

    // Consultar el material
    $query = "SELECT * FROM materiales WHERE id = ?";
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("i", $material_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $material = $result->fetch_assoc();

        // Si se encuentra el material
        if ($material) {
            // Aquí iría el formulario de edición
            echo "<form action='editar_material.php' method='POST'>";
            echo "<input type='hidden' name='id' value='" . $material['id'] . "'>";
            echo "<input type='text' name='nombre_archivo' value='" . $material['nombre_archivo'] . "' required>";
            echo "<button type='submit'>Guardar cambios</button>";
            echo "</form>";

            // Procesar la edición
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $nuevo_nombre = $_POST['nombre_archivo'];

                // Actualizar el material
                $update_query = "UPDATE materiales SET nombre_archivo = ? WHERE id = ?";
                if ($update_stmt = $conn->prepare($update_query)) {
                    $update_stmt->bind_param("si", $nuevo_nombre, $material_id);

                    if ($update_stmt->execute()) {
                        echo "Material actualizado exitosamente";
                    } else {
                        echo "Error al actualizar el material";
                    }

                    $update_stmt->close();
                }
            }
        } else {
            echo "Material no encontrado";
        }

        $stmt->close();
    }
}

$conn->close();
?>

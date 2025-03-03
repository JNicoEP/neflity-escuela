<?php
session_start();
include('conexion.php');

// Verificar si el usuario está logueado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Consultar los materiales dependiendo del rol del usuario
if ($_SESSION['rol_id'] == 1) {
    // Si es administrador, puede ver todos los materiales
    $query = "SELECT * FROM materiales";
} else {
    // Si es docente, solo ver sus propios materiales
    $usuario_id = $_SESSION['user_id'];
    $query = "SELECT * FROM materiales WHERE usuario_id = ?";
}

if ($stmt = $conn->prepare($query)) {
    if ($_SESSION['rol_id'] != 1) {
        $stmt->bind_param("i", $usuario_id);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($material = $result->fetch_assoc()) {
            echo "<div>";
            echo "<p><strong>Nombre:</strong> " . $material['nombre_archivo'] . "</p>";
            echo "<p><strong>Fecha de subida:</strong> " . $material['fecha_subida'] . "</p>";
            echo "<p><a href='" . $material['enlace'] . "' target='_blank'>Ver material</a></p>";

            // Si es admin, puede editar o eliminar el material
            if ($_SESSION['rol_id'] == 1) {
                echo "<a href='editar_material.php?id=" . $material['id'] . "'>Editar</a>";
                echo " | ";
                echo "<a href='eliminar_material.php?id=" . $material['id'] . "'>Eliminar</a>";
            }

            echo "</div>";
        }
    } else {
        echo "No hay materiales disponibles.";
    }

    $stmt->close();
}

$conn->close();
?>

<?php
header('Content-Type: application/json');
include '../conexion.php'; // Ajusta la ruta a conexion.php

// Obtener ID del docente a aprobar
$data = json_decode(file_get_contents("php://input"));
$id = $data->id;

// Verificar rol del usuario antes de aprobar
$sql_rol = "SELECT rol_id FROM usuarios WHERE id = $id";
$result_rol = $conn->query($sql_rol);

if ($result_rol->num_rows > 0) {
    $row_rol = $result_rol->fetch_assoc();
    if ($row_rol['rol_id'] != 3) {
        // Actualizar estado del docente a 'aprobado'
        $sql_update = "UPDATE usuarios SET estado = 'aprobado' WHERE id = $id";
        if ($conn->query($sql_update) === TRUE) {
            $response = array('success' => true);
        } else {
            $response = array('success' => false, 'message' => 'Error al aprobar el docente: ' . $conn->error);
        }
    } else {
        $response = array('success' => false, 'message' => 'No se puede aprobar un alumno.');
    }
} else {
    $response = array('success' => false, 'message' => 'Docente no encontrado.');
}

// Devolver respuesta en formato JSON
echo json_encode($response);
?>
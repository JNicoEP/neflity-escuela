<?php
header('Content-Type: application/json');
include '../conexion.php'; // Ajusta la ruta a conexion.php

// Consulta para obtener docentes pendientes (rol_id != 3)
$sql = "SELECT id, nombre, email FROM usuarios WHERE rol_id != 3 AND estado = 'pendiente'";
$result = $conn->query($sql);

$docentes = array();

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $docentes[] = $row;
    }
}

// Devolver datos en formato JSON
echo json_encode($docentes);
?>
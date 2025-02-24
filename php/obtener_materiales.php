<?php
include 'conexion.php';

$query = "SELECT nombre_archivo, enlace, DATE_FORMAT(fecha_subida, '%d/%m/%Y') as fecha_subida FROM materiales";
$result = $conn->query($query);

$materiales = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $materiales[] = $row;
    }
}

echo json_encode($materiales);

$conn->close();
?>
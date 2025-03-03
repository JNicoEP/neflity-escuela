<?php

$data = json_decode(file_get_contents('php://input'), true);
$nombreArchivo = $data['nombreArchivo'];
$nuevoNombre = $data['nuevoNombre'];
$aula = $data['aula'];

$rutaArchivo = "documentos/" . $aula . "/" . $nombreArchivo;
$nuevaRutaArchivo = "documentos/" . $aula . "/" . $nuevoNombre;

if (file_exists($rutaArchivo) && rename($rutaArchivo, $nuevaRutaArchivo)) {
    $response = array("success" => true, "archivos" => obtenerArchivosActualizados($aula));
} else {
    $response = array("success" => false);
}

header('Content-Type: application/json');
echo json_encode($response);

function obtenerArchivosActualizados($aula) {
    $rutaCarpeta = "documentos/" . $aula;
    $archivos = array();
    if (is_dir($rutaCarpeta)) {
        $archivosLista = scandir($rutaCarpeta);
        foreach ($archivosLista as $archivo) {
            if ($archivo != "." && $archivo != "..") {
                $archivos[] = array("nombre_archivo" => $archivo, "aula" => $aula);
            }
        }
    }
    return $archivos;
}
?>
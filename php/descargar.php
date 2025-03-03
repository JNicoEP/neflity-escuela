<?php
error_reporting(E_ALL); ini_set('display_errors', 1);
$data = json_decode(file_get_contents('php://input'), true);
$nombreArchivo = $data['nombreArchivo'];
$aula = $data['aula'];

$rutaArchivo = "documentos/" . $aula . "/" . $nombreArchivo;

if (file_exists($rutaArchivo)) {
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . basename($rutaArchivo) . '"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($rutaArchivo));
    readfile($rutaArchivo);
    exit;
} else {
    header('Content-Type: application/json');
    echo json_encode(array("success" => false, "message" => "El archivo no existe."));
}
?>
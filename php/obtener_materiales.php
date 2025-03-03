<?php
$curso = $_GET["curso"];
$targetDir = "documentos/" . $curso . "/";
$files = array();

if (file_exists($targetDir)) {
    $fileList = scandir($targetDir);
    foreach ($fileList as $file) {
        if ($file != "." && $file != "..") {
            $filePath = $targetDir . $file;
            if (is_readable($filePath)) { // Verificar si el archivo es legible
                $files= array(
                    "nombre_archivo" => $file,
                    "enlace" => $filePath,
                    "fecha_subida" => date("Y-m-d H:i:s", filemtime($filePath))
                );
            } else {
                error_log("Error al leer el archivo: " . $filePath); // Registrar error en el log
            }
        }
    }
} else {
    error_log("La carpeta no existe: " . $targetDir); // Registrar error en el log
}

header('Content-Type: application/json');
echo json_encode($files);
?>
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["material"])) {
    $curso = $_POST["curso"];
    $targetDir = "documentos/" . $curso . "/";
    $targetFile = $targetDir . basename($_FILES["material"]["name"]);
    $uploadOk = 1;
    $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    if (!file_exists($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    if (file_exists($targetFile)) {
        $response = array("success" => false, "message" => "El archivo ya existe.");
        $uploadOk = 0;
    }

    if ($_FILES["material"]["size"] > 12000000) {
        $response = array("success" => false, "message" => "El archivo es demasiado grande.");
        $uploadOk = 0;
    }

    $allowedFormats = array("pdf", "docx", "xlsx", "jpg", "png");
    if (!in_array($fileType, $allowedFormats)) {
        $response = array("success" => false, "message" => "Solo se permiten archivos PDF, DOCX, XLSX, JPG y PNG.");
        $uploadOk = 0;
    }

    if ($uploadOk == 0) {
        // Ya se ha establecido la respuesta de error
    } else {
        if (move_uploaded_file($_FILES["material"]["tmp_name"], $targetFile)) {
            $response = array("success" => true, "message" => "El archivo " . basename($_FILES["material"]["name"]) . " ha sido subido.", "archivos" => obtenerArchivosActualizados($curso));
        } else {
            $response = array("success" => false, "message" => "Hubo un error al subir tu archivo.");
        }
    }

    header('Content-Type: application/json');
    echo json_encode($response);
}

function obtenerArchivosActualizados($aula) {
    error_log("Obteniendo archivos para el aula: " . $aula);
    $rutaCarpeta = "documentos/" . $aula;
    $archivos = array();
    if (is_dir($rutaCarpeta)) {
        $archivosLista = scandir($rutaCarpeta);
        foreach ($archivosLista as $archivo) {
            if ($archivo != "." && $archivo != "..") {
                $rutaArchivo = $rutaCarpeta . "/" . $archivo;
                $fechaModificacion = date("Y-m-d H:i:s", filemtime($rutaArchivo));
                $archivos[] = array("nombre_archivo" => $archivo, "aula" => $aula, "fecha_subida" => $fechaModificacion);
            }
        }
    }
    return $archivos;
}
?>
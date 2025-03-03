<?php
if (isset($_POST['curso']) && isset($_POST['filterType'])) {
    $curso = $_POST['curso'];
    $filterType = $_POST['filterType'];

    // Definir ruta según el curso
    $ruta = "documentos/" . $curso . "/";

    // Verificar si la carpeta existe
    if (is_dir($ruta)) {
        $archivos = scandir($ruta);
        $archivos_filtrados = [];

        foreach ($archivos as $archivo) {
            if ($archivo != "." && $archivo != "..") {
                $extension = strtolower(pathinfo($archivo, PATHINFO_EXTENSION));

                // Filtrar por tipo de archivo si se selecciona un tipo específico
                if ($filterType == 'all' || $extension == $filterType) {
                    $archivos_filtrados[] = [
                        'nombre_archivo' => $archivo,
                        'enlace' => $ruta . $archivo,
                        'fecha_subida' => date("Y-m-d H:i:s", filemtime($ruta . $archivo))
                    ];
                }
            }
        }

        echo json_encode($archivos_filtrados);
    } else {
        echo json_encode([]);
    }
}
?>
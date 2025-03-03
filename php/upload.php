if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $file = $_FILES['file'];
    $fileName = $file['name'];
    $fileTmpName = $file['tmp_name'];
    $fileSize = $file['size'];
    $fileError = $file['error'];

    // Validar si hay error
    if ($fileError === 0) {
        // Validar el tamaño y el tipo de archivo
        if ($fileSize < 10000000) { // 10MB máximo
            $fileDestination = 'uploads/' . basename($fileName);
            if (move_uploaded_file($fileTmpName, $fileDestination)) {
                echo "Archivo subido correctamente!";
            } else {
                echo "Hubo un error al subir el archivo.";
            }
        } else {
            echo "El archivo es demasiado grande.";
        }
    } else {
        echo "Hubo un error al cargar el archivo.";
    }
}

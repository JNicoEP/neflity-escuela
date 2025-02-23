<?php
$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "aula_virtual";

try {
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        throw new Exception("Conexión fallida: " . $conn->connect_error);
    }
     "Conectado exitosamente a la base de datos"; // Comentar o eliminar en producción

    // Realizar operaciones con la base de datos aquí...

    $conn->close(); // Cerrar la conexión

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
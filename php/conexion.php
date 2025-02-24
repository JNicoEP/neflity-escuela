<?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "aula_virtual";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die(json_encode(["status" => "error", "message" => "Conexión fallida: " . $conn->connect_error]));
    } else {
        echo json_encode(["status" => "success", "message" => "Conexión exitosa con la base de datos"]);
    }
?>
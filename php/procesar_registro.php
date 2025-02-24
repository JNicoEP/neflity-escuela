<?php
    ob_start();
    include 'conexion.php';
    $conexion_response = ob_get_clean();

    $response_data = json_decode($conexion_response, true);

    if ($response_data['status'] === 'success') {
        echo "Conexión a la base de datos verificada. ";
    } else {
        echo "Error en la conexión a la base de datos. ";
        exit;
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nombre = $_POST['nombre'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        $sql = "INSERT INTO usuarios (nombre, email, password) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            die(json_encode(["status" => "error", "message" => "Error en la consulta SQL."]));
        }

        $stmt->bind_param("sss", $nombre, $email, $password);

        if ($stmt->execute()) {
            echo "Registro exitoso";
        } else {
            echo "Error al registrar usuario: " . $stmt->error;
        }

        $stmt->close();
        $conn->close();
    } else {
        echo "Método no permitido.";
    }
?>
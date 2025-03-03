<?php
header('Content-Type: application/json');

include 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['nombre']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['rol_id'])) {
        $nombre = $_POST['nombre'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $rol_id = $_POST['rol_id'];

        // Verificamos si el correo electrónico ya existe
        $sql_check = "SELECT * FROM usuarios WHERE email = ?";
        $stmt_check = $conn->prepare($sql_check);
        $stmt_check->bind_param("s", $email);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();

        if ($result_check->num_rows > 0) {
            echo json_encode(["status" => "error", "message" => "El correo electrónico ya está registrado."]);
            exit;
        }

        $sql = "INSERT INTO usuarios (rol_id, nombre, email, password) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            echo json_encode(["status" => "error", "message" => "Error en la consulta SQL: " . $conn->error]);
            exit;
        }

        $stmt->bind_param("isss", $rol_id, $nombre, $email, $password);

        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "Registro exitoso", "redirect" => "login"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Error al registrar usuario: " . $stmt->error]);
        }

        $stmt->close();
        $conn->close();
    } else {
        echo json_encode(["status" => "error", "message" => "Faltan datos del formulario."]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Método no permitido."]);
}
?>
<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

include 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['email']) && isset($_POST['password'])) {
        $email = trim($_POST['email']);
        $password = $_POST['password'];

        error_log("Datos recibidos: email=" . $email . ", password=" . $password);

        try {
            $sql = "SELECT * FROM usuarios WHERE LOWER(email) = LOWER(?)";
            error_log("Consulta SQL: " . $sql . ", email: " . $email);
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows === 1) {
                $row = $result->fetch_assoc();
                error_log("Usuario encontrado: " . json_encode($row));

                if (password_verify($password, $row['password'])) {
                    $_SESSION['usuario_id'] = $row['id'];
                    $rol_id = $row['rol_id'];

                    if ($rol_id == 2) { // Profesor: verifica el estado "aprobado"
                        if ($row['estado'] === 'aprobado') {
                            $response = json_encode(["status" => "success", "message" => "Inicio de sesión exitoso", "rol_id" => $rol_id, "redirect" => "paneles/profesores.html"]);
                            error_log("Respuesta del servidor: " . $response);
                            echo $response;
                        } else {
                            $response = json_encode(["status" => "error", "message" => "Usuario no aprobado. Contacta al administrador."]);
                            error_log("Respuesta del servidor: " . $response);
                            echo $response;
                        }
                    } else if ($rol_id == 3) { // Alumno: siempre aprobado
                        $response = json_encode(["status" => "success", "message" => "Inicio de sesión exitoso", "rol_id" => $rol_id, "redirect" => "paneles/alumnos.html"]);
                        error_log("Respuesta del servidor: " . $response);
                        echo $response;
                    } else if ($rol_id == 1) { // Admin: no verifica el estado
                        $response = json_encode(["status" => "success", "message" => "Inicio de sesión exitoso", "rol_id" => $rol_id, "redirect" => "paneles/admin_panel.html"]);
                        error_log("Respuesta del servidor: " . $response);
                        echo $response;
                    } else { // Otro rol: redirige al inicio
                        $response = json_encode(["status" => "success", "message" => "Inicio de sesión exitoso", "rol_id" => $rol_id, "redirect" => "index.html"]);
                        error_log("Respuesta del servidor: " . $response);
                        echo $response;
                    }
                } else {
                    error_log("Error: password_verify() falló para el usuario: " . $email);
                    $response = json_encode(["status" => "error", "message" => "Contraseña incorrecta."]);
                    error_log("Respuesta del servidor: " . $response);
                    echo $response;
                }
            } else {
                $response = json_encode(["status" => "error", "message" => "Usuario no encontrado."]);
                error_log("Respuesta del servidor: " . $response);
                echo $response;
            }
        } catch (Exception $e) {
            error_log("Error en la base de datos: " . $e->getMessage());
            $response = json_encode(["status" => "error", "message" => "Error en la base de datos."]);
            error_log("Respuesta del servidor: " . $response);
            echo $response;
        }
    } else {
        $response = json_encode(["status" => "error", "message" => "Faltan datos del formulario."]);
        error_log("Respuesta del servidor: " . $response);
        echo $response;
    }
} else {
    $response = json_encode(["status" => "error", "message" => "Método no permitido."]);
    error_log("Respuesta del servidor: " . $response);
    echo $response;
}

$conn->close();
?>
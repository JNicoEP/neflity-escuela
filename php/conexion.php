<?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "aula_virtual";

    $conn = new mysqli($servername, $username, $password, $dbname);

    try {
      $conn = new mysqli($servername, $username, $password, $dbname);
      if ($conn->connect_error) {
          error_log("Error de conexión: " . $conn->connect_error);
          die("Error de conexión: " . $conn->connect_error);
      }
  } catch (Exception $e) {
      error_log("Error de conexión: " . $e->getMessage());
      die("Error de conexión: " . $e->getMessage());
  }
?>
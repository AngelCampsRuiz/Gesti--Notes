<?php
$host = 'localhost';
$db = 'bd_escuela';
$user = 'root';
$password = '';

try {
    // Crear conexión
    $conexion = mysqli_connect($host, $user, $password, $db);

    // Verificar conexión
    if (!$conexion) {
        throw new Exception("Error de conexión: " . mysqli_connect_error());
    }

    echo "Conexión exitosa a la base de datos.";
} catch (Exception $e) {
    echo $e->getMessage();
    exit;
}
?> 
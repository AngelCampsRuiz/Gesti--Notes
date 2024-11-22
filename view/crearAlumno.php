<?php
// Incluir el archivo de conexión
include '../database/conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $email = $_POST['email'];
    $telefono = $_POST['telefono'];
    $direccion = $_POST['direccion'];
    $fecha_nacimiento = $_POST['fecha_nacimiento'];

    // Preparar la consulta
    $stmt = mysqli_prepare($conexion, "INSERT INTO tbl_alumnos (nombre_alu, apellido_alu, email_alu, telefono_alu, direccion_alu, fecha_nacimiento) VALUES (?, ?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "ssssss", $nombre, $apellido, $email, $telefono, $direccion, $fecha_nacimiento);

    if (mysqli_stmt_execute($stmt)) {
        echo "<script>alert('Alumno creado exitosamente.'); window.location.href='gestionUsers.php';</script>";
    } else {
        echo "<script>alert('Error: " . mysqli_stmt_error($stmt) . "');</script>";
    }

    mysqli_stmt_close($stmt);
}

mysqli_close($conexion);
?>
<form method="post">
    Nombre: <input type="text" name="nombre" required><br>
    Apellido: <input type="text" name="apellido" required><br>
    Email: <input type="email" name="email" required><br>
    Teléfono: <input type="text" name="telefono"><br>
    Dirección: <input type="text" name="direccion"><br>
    Fecha de Nacimiento: <input type="date" name="fecha_nacimiento" required><br>
    <input type="submit" value="Crear Alumno">
</form> 
<?php
// Conexión a la base de datos
$conn = mysqli_connect('localhost', 'usuario', 'contraseña', 'bd_escuela');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $email = $_POST['email'];
    // Otros campos...

    // Preparar la consulta
    $stmt = mysqli_prepare($conn, "INSERT INTO tbl_alumnos (nombre_alu, apellido_alu, email_alu) VALUES (?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "sss", $nombre, $apellido, $email);

    if (mysqli_stmt_execute($stmt)) {
        echo "Alumno creado exitosamente.";
    } else {
        echo "Error: " . mysqli_stmt_error($stmt);
    }

    mysqli_stmt_close($stmt);
}

mysqli_close($conn);
?>
<form method="post">
    Nombre: <input type="text" name="nombre"><br>
    Apellido: <input type="text" name="apellido"><br>
    Email: <input type="email" name="email"><br>
    <input type="submit" value="Crear Alumno">
</form> 
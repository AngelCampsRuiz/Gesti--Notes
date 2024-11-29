<?php
session_start();
if(!isset($_SESSION['id_usu'])){
    header("Location: ../index.php");
    exit();
}
// Incluir el archivo de conexión
include '../database/conexion.php';

try{
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $nombre = mysqli_real_escape_string($conexion,htmlspecialchars(trim($_POST['nombre'])));
        $apellido = mysqli_real_escape_string($conexion,htmlspecialchars(trim($_POST['apellido'])));
        $email = mysqli_real_escape_string($conexion,htmlspecialchars(trim($_POST['email'])));
        $telefono = mysqli_real_escape_string($conexion,htmlspecialchars(trim($_POST['telefono'])));
        $direccion = mysqli_real_escape_string($conexion,htmlspecialchars(trim($_POST['direccion'])));
        $fecha_nacimiento = mysqli_real_escape_string($conexion,htmlspecialchars(trim($_POST['fecha_nacimiento'])));

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
} catch(Exception $e) {
    echo "Error: ". $e;
    exit();
}

mysqli_close($conexion);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/form.css">
    <title>Document</title>
</head>
<body>
<form method="post">
    <label>DNI: <input type="text" id="dni" name="dni"></label>
    <span id="errorDNI" class="error"></span>
    <label>Username: <input type="text" id="username" name="username"></label>
    <span id="errorUsername" class="error"></span>
    <label>Nombre: <input type="text" id="nombre" name="nombre"></label>
    <span id="errorNombre" class="error"></span>
    <label>Apellido: <input type="text" id="apellido" name="apellido"></label>
    <span id="errorApellido" class="error"></span>
    <label>Email: <input type="email" id="email" name="email"></label>
    <span id="errorEmail" class="error"></span>
    <label>Teléfono: <input type="text" id="telefono" name="telefono"></label>
    <span id="errorTelefono" class="error"></span>
    <label>Dirección: <input type="text" id="direccion" name="direccion"></label>
    <span id="errorDireccion" class="error"></span>
    <label>Fecha de Nacimiento: <input type="date" id="fecha" name="fecha_nacimiento"></label>
    <span id="errorDia" class="error"></span><br>
    <div class="button-group">
        <input type="submit" id="boton" value="Crear Alumno" disabled>
        <button type="button" class="btn btn-danger" onclick="window.location.href='gestionUsers.php'">VOLVER</button>
    </div>
</form> 
<script src="../js/verifAlu.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
<?php
session_start();
if(!isset($_SESSION['id_usu'])){
    header("Location: ../index.php");
    exit();
}
include '../database/conexion.php'; // Incluir el archivo de conexión

try {
    // Conexión a la base de datos
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Procesar la actualización del alumno
        $id = mysqli_real_escape_string($conexion,htmlspecialchars(trim($_POST['id'])));
        $nombre = mysqli_real_escape_string($conexion,htmlspecialchars(trim($_POST['nombre'])));
        $apellido = mysqli_real_escape_string($conexion,htmlspecialchars(trim($_POST['apellido'])));
        $email = mysqli_real_escape_string($conexion,htmlspecialchars(trim($_POST['email'])));
        $telefono = mysqli_real_escape_string($conexion,htmlspecialchars(trim($_POST['telefono'])));
        $fecha = mysqli_real_escape_string($conexion,htmlspecialchars(trim($_POST['fecha'])));
        $direccion = mysqli_real_escape_string($conexion,htmlspecialchars(trim($_POST['direccion'])));
        // Otros campos...

        try{
            $sql = "UPDATE tbl_alumnos SET nombre_alu=?, apellido_alu=?, email_alu=?, telefono_alu=?, fecha_nacimiento=?, direccion_alu=? WHERE id_alu=?";
            $stmt = mysqli_prepare($conexion, $sql); // Usar la conexión desde el archivo incluido
            if (!$stmt) {
                throw new Exception("Error al preparar la consulta: " . mysqli_error($conexion));
            }

            mysqli_stmt_bind_param($stmt, "ssssssi", $nombre, $apellido, $email, $telefono, $fecha, $direccion, $id);
            if (!mysqli_stmt_execute($stmt)) {
                throw new Exception("Error al ejecutar la consulta: " . mysqli_stmt_error($stmt));
            }
        } catch (Exception $e) {
            echo "Error al actualizar el alumno: " . $e->getMessage();
            exit();
        }
        mysqli_stmt_close($stmt);
        mysqli_close($conexion); // Cerrar la conexión desde el archivo incluido

        // Redirigir a la página de gestión de usuarios
        header("Location: gestionUsers.php");
        exit; // Asegurarse de que el script se detenga después de la redirección

    } else {
        // Obtener los datos actuales del alumno
        try{
            $id = mysqli_real_escape_string($conexion,$_GET['id']);
            $stmt = mysqli_prepare($conexion, "SELECT * FROM tbl_alumnos WHERE id_alu=?"); // Usar la conexión desde el archivo incluido
            if (!$stmt) {
                throw new Exception("Error al preparar la consulta: " . mysqli_error($conexion));
            }

            mysqli_stmt_bind_param($stmt, "i", $id);
            if (!mysqli_stmt_execute($stmt)) {
                throw new Exception("Error al ejecutar la consulta: " . mysqli_stmt_error($stmt));
            }
        } catch (Exception $e) {
            echo "Error al obtener los datos del alumno: " . $e->getMessage();
            exit();
        }

        $result = mysqli_stmt_get_result($stmt);
        if (!$result) {
            throw new Exception("Error al obtener el resultado: " . mysqli_stmt_error($stmt));
        }

        $alumno = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);
    }

    mysqli_close($conexion); // Cerrar la conexión desde el archivo incluido

} catch (Exception $e) {
    echo "Se produjo un error: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/form.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Document</title>
</head>
    <body>
    <!-- Formulario de edición -->
        <form method="post">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($alumno['id_alu'] ?? ''); ?>">
            <label>Nombre: <input type="text" name="nombre" id="nombre" value="<?php echo htmlspecialchars($alumno['nombre_alu'] ?? ''); ?>"></label>
            <p id="errorNombre"></p>
            <label>Apellido: <input type="text" name="apellido" id="apellido" value="<?php echo htmlspecialchars($alumno['apellido_alu'] ?? ''); ?>"></label>
            <p id="errorApellido"></p>
            <label>Email: <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($alumno['email_alu'] ?? ''); ?>"></label>
            <p id="errorEmail"></p>
            <label>Teléfono: <input type="tel" name="telefono" id="telefono" value="<?php echo htmlspecialchars($alumno['telefono_alu'] ?? ''); ?>"></label>
            <p id="errorTelefono"></p>
            <label>Fecha De Nacimiento: <input type="date" name="fecha" id="fecha" value="<?php echo htmlspecialchars($alumno['fecha_nacimiento'] ?? ''); ?>"> </label>
            <p id="errorDia"></p>
            <label>Direccion: <input type="textarea" name="direccion" id="direccion" value="<?php echo htmlspecialchars($alumno['direccion_alu'] ?? ''); ?>"></label>
            <p id="errorDireccion"></p>
            <div class="button-group">
                <input type="submit" id="boton" disabled value="Actualizar Alumno">
                <button type="button" class="btn btn-danger" onclick="window.location.href='gestionUsers.php'">VOLVER</button>
            </div>
        </form> 
        <script type="text/javascript" src="../js/verifAlu.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    </body>
</html>
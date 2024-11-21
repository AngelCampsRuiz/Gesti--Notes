<?php
function checkMysqliError($conn) {
    if (mysqli_connect_errno()) {
        throw new Exception("Error de conexión: " . mysqli_connect_error());
    }
}

try {
    // Conexión a la base de datos
    $conn = mysqli_connect('localhost', 'usuario', 'contraseña', 'bd_escuela');
    checkMysqliError($conn);

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Procesar la actualización del alumno
        $id = $_POST['id'];
        $nombre = $_POST['nombre'];
        $apellido = $_POST['apellido'];
        $email = $_POST['email'];
        // Otros campos...

        $sql = "UPDATE tbl_alumnos SET nombre_alu=?, apellido_alu=?, email_alu=? WHERE id_alu=?";
        $stmt = mysqli_prepare($conn, $sql);
        if (!$stmt) {
            throw new Exception("Error al preparar la consulta: " . mysqli_error($conn));
        }

        mysqli_stmt_bind_param($stmt, "sssi", $nombre, $apellido, $email, $id);
        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Error al ejecutar la consulta: " . mysqli_stmt_error($stmt));
        }

        echo "Alumno actualizado exitosamente.";
        mysqli_stmt_close($stmt);

    } else {
        // Obtener los datos actuales del alumno
        $id = $_GET['id'];
        $stmt = mysqli_prepare($conn, "SELECT * FROM tbl_alumnos WHERE id_alu=?");
        if (!$stmt) {
            throw new Exception("Error al preparar la consulta: " . mysqli_error($conn));
        }

        mysqli_stmt_bind_param($stmt, "i", $id);
        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Error al ejecutar la consulta: " . mysqli_stmt_error($stmt));
        }

        $result = mysqli_stmt_get_result($stmt);
        if (!$result) {
            throw new Exception("Error al obtener el resultado: " . mysqli_stmt_error($stmt));
        }

        $alumno = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);
    }

    mysqli_close($conn);

} catch (Exception $e) {
    echo "Se produjo un error: " . $e->getMessage();
}
?>

<!-- Formulario de edición -->
<form method="post">
    <input type="hidden" name="id" value="<?php echo htmlspecialchars($alumno['id_alu']); ?>">
    Nombre: <input type="text" name="nombre" value="<?php echo htmlspecialchars($alumno['nombre_alu']); ?>"><br>
    Apellido: <input type="text" name="apellido" value="<?php echo htmlspecialchars($alumno['apellido_alu']); ?>"><br>
    Email: <input type="email" name="email" value="<?php echo htmlspecialchars($alumno['email_alu']); ?>"><br>
    <input type="submit" value="Actualizar Alumno">
</form> 
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

    $id = $_GET['id'];

    // Preparar la consulta
    $stmt = mysqli_prepare($conn, "DELETE FROM tbl_alumnos WHERE id_alu=?");
    if (!$stmt) {
        throw new Exception("Error al preparar la consulta: " . mysqli_error($conn));
    }

    mysqli_stmt_bind_param($stmt, "i", $id);
    if (!mysqli_stmt_execute($stmt)) {
        throw new Exception("Error al ejecutar la consulta: " . mysqli_stmt_error($stmt));
    }

    echo "Alumno eliminado exitosamente.";

    mysqli_stmt_close($stmt);
    mysqli_close($conn);

} catch (Exception $e) {
    echo "Se produjo un error: " . $e->getMessage();
}
?> 
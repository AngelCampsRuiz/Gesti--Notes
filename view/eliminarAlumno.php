<?php
    session_start();
    if($_SERVER['REQUEST_METHOD'] !== 'GET'){
        header('Location: ../view/gestionUsers.php');
        exit();
    }
    try {
        // Incluir el archivo de conexión
        require_once '../database/conexion.php';

        // Desactivar el autocommit
        mysqli_autocommit($conexion, false);
        mysqli_begin_transaction($conexion);

        // Verificar que los parámetros existen
        if (!isset($_GET['id'])) {
            throw new Exception("Parámetro 'id' insuficiente.");
        }

        $id = $_GET['id'];

        // Eliminar las notas del alumno
        $stmt = mysqli_prepare($conexion, "DELETE FROM tbl_notas WHERE id_alu=?");
        if (!$stmt) {
            throw new Exception("Error al preparar la consulta para eliminar notas: " . mysqli_error($conexion));
        }

        mysqli_stmt_bind_param($stmt, "i", $id);
        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Error al ejecutar la consulta para eliminar notas: " . mysqli_stmt_error($stmt));
        }

        mysqli_stmt_close($stmt);

        // Eliminar el alumno
        $stmt = mysqli_prepare($conexion, "DELETE FROM tbl_alumnos WHERE id_alu=?");
        if (!$stmt) {
            throw new Exception("Error al preparar la consulta para eliminar alumno: " . mysqli_error($conexion));
        }

        mysqli_stmt_bind_param($stmt, "i", $id);
        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Error al ejecutar la consulta para eliminar alumno: " . mysqli_stmt_error($stmt));
        }

        mysqli_stmt_close($stmt);

        mysqli_commit($conexion);
        mysqli_close($conexion);
        $_SESSION['eliminarAlumno'] = true;
        header("Location: gestionUsers.php");
        exit();
    } catch (Exception $e) {
        echo "Se produjo un error: " . $e;
    }
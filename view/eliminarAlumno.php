<?php
    if($_SERVER['REQUEST_METHOD'] !== 'GET'){
        header('Location: ../view/gestionUsers.php');
        exit();
    }
    try {
        // Conexión a la base de datos
        require_once '../process/conexion.php';

        $id = $_GET['id'];
        $idAsignatura = $_GET['idAsignatura'];

        // Preparar la consulta
        $stmt = mysqli_prepare($conn, "DELETE FROM tbl_alumnos WHERE id_alu=? AND id_asig=?");
        if (!$stmt) {
            throw new Exception("Error al preparar la consulta: " . mysqli_error($conn));
        }

        mysqli_stmt_bind_param($stmt, "ii", $id,$idAsignatura);
        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Error al ejecutar la consulta: " . mysqli_stmt_error($stmt));
        }

        echo "Alumno eliminado exitosamente.";

        mysqli_stmt_close($stmt);

        $sqlDeleteAlumno = "DELETE FROM tbl_alumnos WHERE id_alu=?";
        $stmt = mysqli_prepare($conn, $sqlDeleteAlumno);
        if (!$stmt) {
            die("Error al preparar la consulta: ". mysqli_error($conn));
        } else {
            mysqli_stmt_bind_param($stmt, "i", $id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close(statement: $stmt);
        }
        mysqli_close($conn);
        header("Location: gestionUsers.php");
        exit();
    } catch (Exception $e) {
        echo "Se produjo un error: " . $e->getMessage();
    }
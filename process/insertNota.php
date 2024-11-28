<?php
session_start();
if (!isset($_SESSION['id_usu'])) {
    header('Location: ../index.php');
    exit();
    if($_SERVER['REQUEST_METHOD'] !== 'POST'){
        header('Location: ../process/insertNota.php');
    }
}

require_once '../database/conexion.php';

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id_alu = mysqli_real_escape_string($conexion, $_POST['id_alu']);
        $id_asig = mysqli_real_escape_string($conexion, $_POST['asignatura']);
        $nota = mysqli_real_escape_string($conexion, $_POST['nota']);
        $fecha_registro = date('Y-m-d');

        $sql = "INSERT INTO tbl_notas (id_alu, id_asig, nota_alu, fecha_registro) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($conexion, $sql);

        if (!$stmt) {
            throw new Exception("Error al preparar la consulta: " . mysqli_error($conexion));
        }

        mysqli_stmt_bind_param($stmt, "iids", $id_alu, $id_asig, $nota, $fecha_registro);
        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Error al insertar la nota: " . mysqli_stmt_error($stmt));
        }

        mysqli_stmt_close($stmt);
        mysqli_close($conexion);

        echo "Nota insertada correctamente.";
        header("Location: ../view/gestionUsers.php");
        exit();
    } else {
        header('Location: ../view/notaAlumno.php');
        exit();
    }
} catch (Exception $e) {
    echo "Se produjo un error: " . $e->getMessage();
    if (isset($stmt)) {
        mysqli_stmt_close($stmt);
    }
    mysqli_close($conexion);
}
?>
<?php
    session_start();
    if(!isset($_SESSION['id_usu'])){
        header('Location: ../index.php');
        exit();
    }
    if($_SERVER['REQUEST_METHOD'] !== 'POST'){
        header('Location: ../process/insertNota.php');
        exit();
    } else {
        try{
            require_once 'conexion.php';
            $idAlu = mysqli_real_escape_string($conn, htmlspecialchars(trim($_POST['id_usu'])));
            $nombre = htmlspecialchars(trim($_POST['asignatura']));
            $nota = htmlspecialchars(trim($_POST['nota']));
            $idAsigSQL = mysqli_real_escape_string($conn, $nombre);
            $notaSQL = mysqli_real_escape_string($conn, $nota); 

            $fecha = date('Y-m-d');
            $sqlNota = "INSERT INTO tbl_notas (id_alu, id_asig, nota_alu, fecha_registro) VALUES (?,?,?,?)";
            $stmtNota = mysqli_stmt_init($conn);
            mysqli_stmt_prepare($stmtNota, $sqlNota);
            mysqli_stmt_bind_param($stmtNota, "iids", $idAlu, $idAsigSQL, $notaSQL, $fecha);
            mysqli_stmt_execute($stmtNota);
            header("Location: ../view/gestionUsers.php");
            exit();
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            exit();
        }
    }
<?php
session_start();

include_once '../database/conexion.php';

if (!isset($_POST['botton'])) {
    header('Location: ../view/cerrarSesion.php');
    exit();
}

if (empty($_POST['user']) || empty($_POST['pwd'])) {
    mysqli_close($conexion);
    header("Location: ../view/cerrarSesion.php");
    exit();
} elseif (!preg_match('/^[a-zA-Z0-9]+$/', $_POST['user'])) {
    mysqli_close($conexion);
    header("Location: ../index.php?loginError");
    exit();
}

$user = mysqli_real_escape_string($conexion, htmlspecialchars($_POST['user']));
$pwd = mysqli_real_escape_string($conexion, htmlspecialchars($_POST['pwd']));
$_SESSION['user'] = $user;

$query = "SELECT id_usu, password_usu FROM tbl_usuarios WHERE username_usu = ?";
$stmt = mysqli_stmt_init($conexion);

if (mysqli_stmt_prepare($stmt, $query)) {
    mysqli_stmt_bind_param($stmt, 's', $user);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {

        if (password_verify($pwd, $row['password_usu'])) {
            $_SESSION['id_usu'] = $row['id_usu'];

            mysqli_stmt_close($stmt);
            mysqli_close($conexion);
            header("Location: ../view/gestionUsers.php");
            exit();
        }
    }
    
    mysqli_stmt_close($stmt);
    mysqli_close($conexion);
    header("Location: ../index.php?loginError");
    exit();
}

mysqli_close($conexion);
header("Location: ../index.php");
exit();
?>
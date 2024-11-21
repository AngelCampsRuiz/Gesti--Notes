<?php
session_start();

include_once 'conexion.php';

if (!isset($_POST['inicio'])) {
    header('Location: cerrarSesion.php');
    exit();
}

if (empty($_POST['user']) || empty($_POST['pwd'])) {
    mysqli_close($conn);
    header("Location: cerrarSesion.php");
    exit();
} elseif (!preg_match('/^[a-zA-Z0-9]+$/', $_POST['user'])) {
    mysqli_close($conn);
    header("Location: ../index.php?loginError");
    exit();
}

$user = mysqli_real_escape_string($conn, htmlspecialchars($_POST['user']));
$pwd = mysqli_real_escape_string($conn, htmlspecialchars($_POST['pwd']));

$query = "SELECT id_usu, password_usu FROM tbl_usuarios WHERE username_usu = ?";
$stmt = mysqli_stmt_init($conn);

if (mysqli_stmt_prepare($stmt, $query)) {
    mysqli_stmt_bind_param($stmt, 's', $user);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {

        if (password_verify($pwd, $row['password_usu'])) {
            $_SESSION['id_usu'] = $row['id_usu'];

            mysqli_stmt_close($stmt);
            mysqli_close($conn);
            exit();
        }
    }
    
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    header("Location: ../index.php?loginError");
    exit();
}

mysqli_close($conn);
header("Location: ../view/index.php");
exit();
?>
<?php
session_start();

include_once 'conexion.php';

$errors = [];

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
    header("Location: cerrarSesion.php");
    exit();
}

$user = mysqli_real_escape_string($conn, htmlspecialchars($_POST['user']));
$pwd = mysqli_real_escape_string($conn, htmlspecialchars($_POST['pwd']));

$query = "SELECT id_camarero, password FROM tbl_camarero WHERE username = ?";
$stmt = mysqli_stmt_init($conn);

if (mysqli_stmt_prepare($stmt, $query)) {
    mysqli_stmt_bind_param($stmt, 's', $username);
    mysqli_stmt_execute($stmt); 
    $result = mysqli_stmt_get_result($stmt);

    // Comprueba si hay resultado
    if ($row = mysqli_fetch_assoc($result)) {

        // Verificamos que la contraseña sea correcta
        if (password_verify($password, $row['password'])) {
            // En caso que sea correcto, inicializamos la variable de SESSION y redirijimos a mesas.php con el ID del usuario
            session_start();
            $_SESSION['user_id'] = $row['id_camarero'];

            // Cerramos las consultas y la conexión
            mysqli_stmt_close($stmt);
            mysqli_close($conn);

            // Redirección a mesas.php con SweetAlert
            echo "<script type='text/javascript'>
                Swal.fire({
                    title: 'Inicio de sesión',
                    text: '¡Has iniciado sesión correctamente!',
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then(function() {
                    window.location.href = '../view/mesas.php';
                });
                </script>";
            exit();
        }
    }
    


    mysqli_stmt_close($stmt);
}

mysqli_close($conn);
header("Location: ../view/index.php");
exit();
?>
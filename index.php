<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" type="text/css" href="./CSS/styles.css">
</head>
<body>
    <header>
        <img src="../Imagenes/LogoEscuela.jpeg" id="logo">
        <h2 id="iniciar-sesion">Iniciar sesión</h2>
    </header>
    <form action="php/validarLogin.php" method="POST" id="login">
        <label for="user">Usuario:</label><br><br>
        <input type="text" id="user" name="user" value="<?php if (isset($_SESSION['usuario'])) echo $_SESSION['usuario']; ?>" onblur="validarUser()" onkeyup="validarUser()"><br>
        <span id="errorUser" class="error"></span><br>
        <label for="pwd">Contrasena:</label><br><br>
        <input type="password" id="pwd" name="pwd"><br>
        <span id="errorPwd" class="error"></span><br>
        <?php if (isset($_GET['loginError'])) {
            echo "<p style='text-align: center;'>Usuario o contraseña incorrecto.</p><br>";
        } ?>
        <input type="submit" name="boton" id="boton" value="Iniciar Sesión" disabled>
    </form>

    <script type="text/javascript" src="js/verifLogin.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <?php
        session_unset();
        session_destroy();
    ?>
</body>
</html>
<?php
    session_start();
    if(!isset($_SESSION['id_usu'])){
        header('Location: ../index.php');
        exit();
    }
    if($_SERVER['REQUEST_METHOD'] !== 'POST'){
        header('Location: notaAlumno.php');
        exit();
    }
    $id_alu = htmlspecialchars(trim($_POST['id']));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/form.css">
    <title>Document</title>
</head>
<body>
    <a href="notaAlumno.php?id=<?php echo $id_alu; ?>"><button class="btn btn-danger">VOLVER</button></a>
    <form method="POST" action="../process/insertNota.php">
        <input type="hidden" name="id_alu" value="<?php echo $id_alu; ?>">
        <label>Asignatura:
        <select name="asignatura" id="asignatura" required>
            <option value="" disabled selected>Seleccione una asignatura</option>
            <?php
                require_once '../database/conexion.php';
                $id_alu = mysqli_real_escape_string($conexion, htmlspecialchars(trim($_POST['id'])));
                $sql = "SELECT asig.id_asig, asig.nombre_asig FROM tbl_asignatura asig LEFT JOIN tbl_notas n ON n.id_asig = asig.id_asig AND n.id_alu = ? WHERE n.id_alu IS NULL";
                    $stmt = mysqli_stmt_init($conexion);
                    if (mysqli_stmt_prepare($stmt, $sql)) {
                        mysqli_stmt_bind_param($stmt, "i", $id_alu);
                        mysqli_stmt_execute($stmt);
                        $resultados = mysqli_stmt_get_result($stmt);
                        while ($row = mysqli_fetch_assoc($resultados)) {
                            echo "<option value='" . $row['id_asig'] . "'>" . $row['nombre_asig'] . "</option>";
                        }
                    } else {
                        echo "Error en la consulta: " . mysqli_error($conexion);
                    }
                mysqli_stmt_close($stmt);
                mysqli_close($conexion);
            ?>
        </select></label>
        <p id="errorAsig"></p>
        <label for="nota">Nota:<input type="number" name="nota" id="nota" step="0.1" required></label>
        <p id="errorNota"></p>
        <input type="submit" id="boton" value="Enviar">
    </form>
    <script src="../js/verifNota.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/form.css">
    <title>Agregar Nota</title>
</head>
<body>
    <form method="POST" action="../process/insertNota.php">
        <input type="hidden" name="id_alu" value="<?php echo $id_alu; ?>">
        <label>Asignatura:
            <select name="asignatura" id="asignatura">
                <option value="" disabled selected>Seleccione una asignatura</option>
                <?php
                    require_once '../database/conexion.php';
                    $id_alu = mysqli_real_escape_string($conexion, htmlspecialchars(trim($_POST['id'])));
                    $sql = "SELECT asig.id_asig, asig.nombre_asig FROM tbl_asignatura asig 
                            LEFT JOIN tbl_notas n ON n.id_asig = asig.id_asig AND n.id_alu = ? 
                            WHERE n.id_alu IS NULL";
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
            </select>
        </label>
        <p id="errorAsig"></p>
        <label for="nota">Nota:
            <input type="number" name="nota" id="nota" step="0.1" placeholder="Example: 8 o 8.5">
        </label>
        <p id="errorNota"></p>
        <div class="button-group">
            <input type="submit" id="boton" value="Enviar" disabled>
            </form>
            <a href="notaAlumno.php?id=<?php echo $id_alu; ?>" class="btn btn-danger">VOLVER</a>
        </div>

    <script src="../js/verifNota.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>
</html>
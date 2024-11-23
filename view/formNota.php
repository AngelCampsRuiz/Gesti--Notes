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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form method="POST" action="../process/insertNota.php">
        <label>Asignatura:</label>
        <select name="asignatura" id="asignatura">
            <option value="" selected-disabled>Seleccione una asignatura</option>
            <?php
                require_once '../process/conexion.php';
                $id_alu = mysqli_real_escape_string($conn,htmlspecialchars(trim($_POST['id'])));
                $sql = "SELECT asig.id_asig, asig.nombre_asig FROM tbl_asignatura asig LEFT JOIN tbl_notas n ON n.id_asig = asig.id_asig AND n.id_alu = ? WHERE n.id_alu IS NULL";
                    $stmt = mysqli_stmt_init($conn);
                    if (mysqli_stmt_prepare($stmt, $sql)) {
                        mysqli_stmt_bind_param($stmt, "i", $id_alu);
                        mysqli_stmt_execute($stmt);
                        $resultados = mysqli_stmt_get_result($stmt);
                        while ($row = mysqli_fetch_assoc($resultados)) {
                            echo "<option value='" . $row['id_asig'] . "'>" . $row['nombre_asig'] . "</option>";
                        }
                    } else {
                        echo "Error en la consulta: " . mysqli_error($conn);
                    }
                mysqli_stmt_close($stmt);
                mysqli_close($conn);
            ?>
        </select>
        <p id="errorAsig"></p>
        <label for="nota">Nota:<input type="number" name="nota" id="nota" step="0.1"></label>
        <p id="errorNota"></p>
        <input type="submit" id="boton" value="Enviar" disabled>
    </form>
    <script src="../js/verifNota.js"></script>
</body>
</html>
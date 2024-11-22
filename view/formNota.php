<?php
    session_start();
    $id_alu = 1;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form>
        <label>Asignatura:</label>
        <select>
            <?php
                require_once '../process/conexion.php';
                $sql = "SELECT a.id_asig, a.nombre_asig FROM tbl_asignatura a INNER JOIN tbl_notas n ON n.id_asig = a.id_asig INNER JOIN tbl_alumnos a ON a.id_alu = a.id_alu WHERE n.id_alu !=  ?";
                $stmt = mysqli_stmt_init($conn);
                mysqli_stmt_prepare($stmt, $sql);
                mysqli_stmt_bind_param($stmt, "i", $id_alu);
                $resultados = mysqli_stmt_get_result($stmt);
                while($row = mysqli_fetch_assoc($resultados)){
                    echo "<option value='".$row['id_asig']."'>".$row['nombre_asig']."</option>";
                }
            ?>
        </select>
        <label for="nota">Nota:<input type="text" name="nota" id="nota"></label>
    </form>
</body>
</html>
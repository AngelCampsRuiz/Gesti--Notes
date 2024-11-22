<?php
    session_start();
    require_once '../process/conexion.php';

    $id = 1;
    $sqlNotas = "SELECT * FROM tbl_alumnos u INNER JOIN tbl_notas n ON n.id_alu = u.id_alu INNER JOIN tbl_asignatura a ON a.id_asig = n.id_asig WHERE u.id_alu = ?";
    $stmtNotas = mysqli_stmt_init($conn);
    mysqli_stmt_prepare($stmtNotas, $sqlNotas);
    mysqli_stmt_bind_param($stmtNotas, "i", $id);
    mysqli_stmt_execute($stmtNotas);
    $resultNotas = mysqli_stmt_get_result($stmtNotas);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form method="POST" action="notaAlumno.php">

    </form>
    <table>
        <tr>
            <th>Nombre de asignatura:</th>
            <th>Nota:</th>
        </tr>
        <?php
            while ($row = mysqli_fetch_array($resultNotas)){
                echo "<tr>";
                echo "<td>". $row['nombre_asig']. "</td>";
                echo "<td>". $row['nota_alu']. "</td>";
                echo "</tr>";
            }
        ?>
    </table>
</body>
</html>
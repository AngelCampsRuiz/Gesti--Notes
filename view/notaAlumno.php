<?php
    session_start();
    if(!isset($_SESSION['id_usu'])){
        header("Location: ../index.php");
        exit();
    }
    if($_SERVER['REQUEST_METHOD'] !== 'GET'){
        header("Location:  gestionUsers.php");
        exit();
    }

    require_once '../process/conexion.php';

    try{
        $idAlu = htmlspecialchars(trim($_GET['id']));
        $id = mysqli_real_escape_string($conn, $idAlu);

        // Consulta para saber las notas del alumno que viene su id por URL
        $sqlNotas = "SELECT * FROM tbl_alumnos u INNER JOIN tbl_notas n ON n.id_alu = u.id_alu INNER JOIN tbl_asignatura a ON a.id_asig = n.id_asig WHERE u.id_alu = ?";
        $stmtNotas = mysqli_stmt_init($conn);
        mysqli_stmt_prepare($stmtNotas, $sqlNotas);
        mysqli_stmt_bind_param($stmtNotas, "i", $id);
        mysqli_stmt_execute($stmtNotas);
        $resultNotas = mysqli_stmt_get_result($stmtNotas);
        
        // Consulta para ver los datos del alumno seleccionado
        $sqlAlumno = "SELECT * FROM tbl_alumnos WHERE id_alu = ?";
        $stmtAlumno = mysqli_stmt_init($conn);
        mysqli_stmt_prepare($stmtAlumno, $sqlAlumno);
        mysqli_stmt_bind_param($stmtAlumno, "i", $id);
        mysqli_stmt_execute($stmtAlumno);
        $resultAlumno = mysqli_stmt_get_result($stmtAlumno);
    } catch (Exception $e) {
        echo "Error: " . $e;
        exit();
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Document</title>
</head>
<body>
    <form method="POST" action="formNota.php">
        <input type="hidden" name="id" id="id" value="<?php echo $idAlu; ?>" />
        <input type="submit" class="btn btn-success" name="boton" value="Subir Nota">
    </form>
    <a href="gestionUsers.php"><button class="btn btn-danger">VOLVER</button></a>
    <h2>Información del alumno</h2>
    <div>
        <?php
            while ($row1 = mysqli_fetch_assoc($resultAlumno)) {
                echo "<p>" . htmlspecialchars($row1['nombre_alu']) . " " . htmlspecialchars($row1['apellido_alu']) . "</p>";
                echo "<p>Correo: " . htmlspecialchars($row1['email_alu']) . "</p>";
                echo "<p>Teléfono: " . htmlspecialchars($row1['telefono_alu']) . "</p>";
                echo "<p>Fecha de nacimiento: " . htmlspecialchars($row1['fecha_nacimiento']) . "</p>";
                echo "<p>Dirección: " . htmlspecialchars($row1['direccion_alu']) . "</p>";
            }
        ?>
    </div>
    <table>
        <thead>
            <tr>
                <th>Nombre de asignatura:</th>
                <th>Nota:</th>
            </tr>
        </thead>
        <tbody>
            <?php
                while ($row = mysqli_fetch_array($resultNotas)){
                    echo "<tr>";
                    echo "<td>". $row['nombre_asig']. "</td>";
                    echo "<td>". $row['nota_alu']. "</td>";
                    echo "</tr>";
                }

                // Cerrar stmts y la conexion a la base de datos
                mysqli_stmt_close($stmtNotas);
                mysqli_stmt_close($stmtAlumno);
                mysqli_close($conn);
            ?>
        </tbody>
    </table>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
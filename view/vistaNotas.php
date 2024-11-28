<?php
    session_start();
    if(!isset($_SESSION['id_usu'])){
        header('Location: ../index.php');
        exit();
    }
    require_once '../process/conexion.php';

    if($_SERVER['REQUEST_METHOD'] !== 'GET' || $_SERVER['REQUEST_METHOD'] !== 'POST'){
        try{
            $sqlNotasAlumnos = "SELECT * FROM tbl_alumnos l INNER JOIN tbl_notas n ON n.id_alu = l.id_alu INNER JOIN tbl_asignatura a ON n.id_asig = a.id_asig";
            $stmtNotasAlumnos = mysqli_stmt_init($conn);
            mysqli_stmt_prepare($stmtNotasAlumnos, $sqlNotasAlumnos);
            mysqli_stmt_execute($stmtNotasAlumnos);
            $resultNotasAlumnos = mysqli_stmt_get_result($stmtNotasAlumnos);
        } catch(Exception $e){
            echo "Error: " . $e;
            exit();
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/vistaNotas.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Notas de Asignaturas</title>
</head>
<body>
<nav class="navbar">
    <a class="navbar-brand" href="#">
        <img src="../img/LogoEscuela.jpeg" alt="Logo" id="logo">
    </a>
    <a href="cerrarSesion.php"><button class='btn btn-danger'>Cerrar Sesion</button></a>
</nav>

<h1>Notas de Asignaturas</h1>
<div class="buttons-container">
    <a href="gestionUsers.php"><button class="btn btn-primary">Vista Alumnos</button></a>
    <a href="vistaNotas.php?asignatura"><button class="btn btn-primary">Media Asignaturas</button></a>
    <a href="vistaNotas.php"><button class="btn btn-danger">Vista Notas</button></a>
</div>

<?php
    require_once '../process/filtrosNotas.php';
        if($resultNotasAlumnos){
            echo "<table>";
                echo "<thead>";
                    echo "<tr>";
                        echo "<th>Asignatura</th>";
                        echo "<th>Media</th>";
                        echo "<th>Alumno</th>";
                        echo "<th>Nota</th>";
                    echo "</tr>";
                echo "</thead>";
                echo "<tbody>";
                while($rowNotasAlumnos = mysqli_fetch_assoc($resultNotasAlumnos)){
                    echo "<tr>";
                        echo "<td>".$rowNotasAlumnos['nombre_asig']."</td>";
                        echo "<td>".(isset($rowNotasAlumnos['promedio']) ? number_format($rowNotasAlumnos['promedio'], 2) : "----------")." </td>";
                        echo "<td>".$rowNotasAlumnos['nombre_alu']."</td>";
                        echo "<td>".number_format($rowNotasAlumnos['nota_alu'], 2)."</td>";
                    echo "</tr>";
                }
                echo "</tbody>";
            echo "</table>";
        } else {
            echo "<p>No hay notas para mostrar</p>";
        }
?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
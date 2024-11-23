<?php
    session_start();
    if(!isset($_SESSION['id_usu'])){
        header('Location: ../index.php');
        exit();
    }
    require_once '../process/conexion.php';

    // Consulta para obtener informacion de los estudiantes con sus notas de cada asignatura.
    try{
        $sqlNotasAlumnos = "SELECT * FROM tbl_alumnos l INNER JOIN tbl_notas n ON n.id_alu = l.id_alu INNER JOIN tbl_asignatura a ON n.id_asig = a.id_asig";
        $stmtNotasAlumnos = mysqli_stmt_init($conn);
        mysqli_stmt_prepare($stmtNotasAlumnos, $sqlNotasAlumnos);
        mysqli_stmt_execute($stmtNotasAlumnos);
        $resultNotasAlumnos = mysqli_stmt_get_result($stmtNotasAlumnos);
    } catch(Exception $e){
        echo "Error: " . $e->getMessage();
        exit();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/styles.css">
    <title>Document</title>
</head>
<body>
    <a href="gestionUsers.php"><button>Vista Alumnos</button></a>
    <table>
        <thead>
            <tr>
                <th>ID Estudiante</th>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Asignatura</th>
                <th>Nota</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = mysqli_fetch_assoc($resultNotasAlumnos)):?>
                <tr>
                    <td><?php echo $row['id_alu'];?></td>
                    <td><?php echo $row['nombre_alu'];?></td>
                    <td><?php echo $row['apellido_alu'];?></td>
                    <td><?php echo $row['nombre_asig'];?></td>
                    <td><?php echo $row['nota_alu'];?></td>
                </tr>
            <?php endwhile;?>
        </tbody>
    </table>
</body>
</html>
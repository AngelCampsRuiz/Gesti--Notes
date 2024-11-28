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
        $sqlNotas = "SELECT * FROM tbl_alumnos u 
                        INNER JOIN tbl_notas n ON n.id_alu = u.id_alu 
                        INNER JOIN tbl_asignatura a ON a.id_asig = n.id_asig 
                        WHERE u.id_alu = ?";
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
        
        // Consulta para saber si el alumno tiene notas de todos las asignaturas
        $sqlAsignaturas = "SELECT a.id_asig, a.nombre_asig FROM tbl_asignatura a LEFT JOIN tbl_notas n ON n.id_asig = a.id_asig AND n.id_alu = ? WHERE n.id_alu IS NULL";
        $stmtAsignaturas = mysqli_stmt_init($conn);
        mysqli_stmt_prepare($stmtAsignaturas, $sqlAsignaturas);
        mysqli_stmt_bind_param($stmtAsignaturas, "i", $id);
        mysqli_stmt_execute($stmtAsignaturas);
        $resultAsignaturas = mysqli_stmt_get_result($stmtAsignaturas);
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Gestión de Alumnos</title>
</head>
<body class="bg-light">
    <!-- Barra de navegación -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="gestionUsers.php">Gestión Escolar</a>
            <!-- Botón visible siempre en móviles -->
            <div class="d-flex ms-auto d-lg-none">
                <a href="gestionUsers.php" class="btn btn-danger">Volver</a>
            </div>
            <!-- Menú colapsable -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item d-none d-lg-block">
                        <!-- Botón visible solo en pantallas grandes -->
                        <a href="gestionUsers.php" class="btn btn-danger">Volver</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>


    <!-- Contenido principal -->
    <div class="container mt-4">
        <div class="row">
            <!-- Información del alumno -->
            <div class="col-lg-6 col-md-12">
                <h2 class="text-primary">Información del Alumno</h2>
                <div class="card shadow-sm mb-3">
                    <div class="card-body">
                        <?php
                            while ($row1 = mysqli_fetch_assoc($resultAlumno)) {
                                echo "<p><strong>Nombre:</strong> " . htmlspecialchars($row1['nombre_alu']) . " " . htmlspecialchars($row1['apellido_alu']) . "</p>";
                                echo "<p><strong>Correo:</strong> " . htmlspecialchars($row1['email_alu']) . "</p>";
                                echo "<p><strong>Teléfono:</strong> " . htmlspecialchars($row1['telefono_alu']) . "</p>";
                                echo "<p><strong>Fecha de nacimiento:</strong> " . htmlspecialchars($row1['fecha_nacimiento']) . "</p>";
                                echo "<p><strong>Dirección:</strong> " . htmlspecialchars($row1['direccion_alu']) . "</p>";
                            }
                        ?>
                    </div>
                </div>
                <?php
                if($resultAsignaturas && mysqli_num_rows($resultAsignaturas) > 0){
                echo "<form method='POST' action='formNota.php'>
                    <input type='hidden' name='id' value='$idAlu'/>
                    <button type='submit' class='btn btn-success w-100 mb-2'>Subir Nota</button>
                </form>";
                } else {
                    echo "";
                }
                ?>
            </div>

            <!-- Notas del alumno -->
            <div class="col-lg-6 col-md-12">
                <h2 class="text-primary">Notas del Alumno</h2>
                <div class="card shadow-sm">
                    <div class="card-body">
                        <?php
                            if($resultNotas && mysqli_num_rows($resultNotas) > 0){
                                echo "<div class='table-responsive'>";
                                    echo "<table class='table table-striped'>";
                                        echo "<thead>";
                                            echo "<tr>";
                                                echo "<th>Asignatura</th>";
                                                echo "<th>Nota</th>";
                                            echo "</tr>";
                                        echo "</thead>";
                                        echo "<tbody>";
                                            while ($row = mysqli_fetch_array($resultNotas)){
                                                echo "<tr>";
                                                echo "<td>". htmlspecialchars($row['nombre_asig']) . "</td>";
                                                echo "<td>". htmlspecialchars($row['nota_alu']) . "</td>";
                                                echo "</tr>";
                                            }
                                        echo "</tbody>";
                                    echo "</table>";
                                echo "</div>";
                            } else {
                                echo "<p class='text-danger'>No hay notas registradas.</p>";
                            }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

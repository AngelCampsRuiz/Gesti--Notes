<?php
    session_start();
    if(!isset($_SESSION['id_usu'])){
        header('Location: ../index.php');
        exit();
    }
    // Hace que salte el sweet alert de acceso correcto solo una vez
    if(isset($_SESSION['loginTrue']) && $_SESSION['loginTrue']){
        $user = $_SESSION['username'];
        echo "<script> let loginSucces = true; let user = '$user';</script>";
        unset($_SESSION['loginTrue']);
    }
    // Hace que salte el sweet alert de subir la nota de un alumno
    if(isset($_SESSION['notaSubida']) && $_SESSION['notaSubida']){
        echo "<script> let notaSubida = true;</script>";
        unset($_SESSION['notaSubida']);
    }
    // Hace que salte el sweet alert de hacer un editar alumno
    if(isset($_SESSION['editarAlumno']) && $_SESSION['editarAlumno']){
        echo "<script> let editarAlumno = true;</script>";
        unset($_SESSION['editarAlumno']);
    }
    // Hace que salte el sweet alert de eliminar un alumno
    if(isset($_SESSION['eliminarAlumno']) && $_SESSION['eliminarAlumno']){
        echo "<script> let eliminarAlumno = true;</script>";
        unset($_SESSION['eliminarAlumno']);
    }

    // Inicializar variables de filtro
    $nombreFiltro = isset($_GET['nombre']) ? $_GET['nombre'] : '';
    $apellidoFiltro = isset($_GET['apellido']) ? $_GET['apellido'] : '';

    // Obtener el número de alumnos por página
    $alumnosPorPagina = isset($_GET['alumnosPorPagina']) ? (int)$_GET['alumnosPorPagina'] : 10;
    $paginaActual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
    $offset = ($paginaActual - 1) * $alumnosPorPagina;

    function checkMysqliError($conexion) {
        if (mysqli_connect_errno()) {
            throw new Exception("Error de conexión: " . mysqli_connect_error());
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion Usuarios</title>
    <link rel="stylesheet" type="text/css" href="./../css/styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.14.5/dist/sweetalert2.min.css" integrity="sha256-qWVM38RAVYHA4W8TAlDdszO1hRaAq0ME7y2e9aab354=" crossorigin="anonymous">
</head>
<body>
    <nav class="navbar">
        <a class="navbar-brand" href="#">
            <img src="../img/LogoEscuela.jpeg" alt="Logo" id="logo">
        </a>
        <a href="cerrarSesion.php"><button class='btn btn-danger btn-sm'>Cerrar Sesion</button></a>
    </nav>

    <div class="search-container">
        <form class="search-form" role="search" method="GET" action="">
            <label>Nombre:</label>
            <input type="search" name="nombre" placeholder="Introduce un nombre" value="<?php echo htmlspecialchars($nombreFiltro); ?>">
            <label>Apellido:</label>
            <input type="search" name="apellido" placeholder="Introduce un apellido" value="<?php echo htmlspecialchars($apellidoFiltro); ?>">
            <button type="submit">Buscar</button>
            <button type="button" onclick="window.location.href='gestionUsers.php'">Borrar Filtros</button>
        </form>
    </div>

    <h1>Estudiantes</h1>
    <div class="buttons-container">
        <a href="crearAlumno.php"><button class="btn btn-success btn-sm">Crear Nuevo Alumno</button></a>
        <a href="vistaNotas.php"><button class="btn btn-info btn-sm">Notas De Alumnos</button></a>
    </div>

    <div class="pagination-control">
        <form method="GET" action="">
            <label for="alumnosPorPagina">Alumnos por página:</label>
            <select name="alumnosPorPagina" id="alumnosPorPagina" onchange="this.form.submit()">
                <option value="5" <?php if ($alumnosPorPagina == 5) echo 'selected'; ?>>5</option>
                <option value="10" <?php if ($alumnosPorPagina == 10) echo 'selected'; ?>>10</option>
                <option value="20" <?php if ($alumnosPorPagina == 20) echo 'selected'; ?>>20</option>
            </select>
            <input type="hidden" name="nombre" value="<?php echo htmlspecialchars($nombreFiltro); ?>">
            <input type="hidden" name="apellido" value="<?php echo htmlspecialchars($apellidoFiltro); ?>">
        </form>
    </div>

    <?php
    try {
        // Incluir el archivo de conexión
        include '../database/conexion.php';
        checkMysqliError($conexion);

        // Preparar la consulta con filtros
        $sql = "SELECT * FROM tbl_alumnos WHERE nombre_alu LIKE ? AND apellido_alu LIKE ? LIMIT ?, ?";
        $stmt = mysqli_prepare($conexion, $sql);
        if (!$stmt) {
            throw new Exception("Error al preparar la consulta: " . mysqli_error($conexion));
        }

        // Aplicar comodines solo para la consulta
        $nombreFiltroConsulta = "$nombreFiltro%";
        $apellidoFiltroConsulta = "$apellidoFiltro%";
        mysqli_stmt_bind_param($stmt, "ssii", $nombreFiltroConsulta, $apellidoFiltroConsulta, $offset, $alumnosPorPagina);
        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Error al ejecutar la consulta: " . mysqli_stmt_error($stmt));
        }

        $result = mysqli_stmt_get_result($stmt);
        if (!$result) {
            throw new Exception("Error al obtener el resultado: " . mysqli_stmt_error($stmt));
        }

        // Mostrar los alumnos
        if (mysqli_num_rows($result) > 0) {
            echo "<table><tr><th>Nombre</th><th>Apellido</th><th class='email'>Email</th><th>Acciones</th></tr>";
            while($row = mysqli_fetch_assoc($result)) {
                $id = $row['id_alu'];
                $nombre = $row['nombre_alu'];
                echo "<tr><td><a href='notaAlumno.php?id={$id}'>$nombre</a></td><td>{$row['apellido_alu']}</td><td class='email'>{$row['email_alu']}</td>";
                echo "<td><a href='editarAlumno.php?id={$row['id_alu']}' class='btn btn-warning btn-sm'>Editar</a> | ";
                echo "<a href='#' class='btn btn-danger btn-sm delete-link' data-id='{$row['id_alu']}' data-toggle='modal' data-target='#confirmDeleteModal'>Eliminar</a></td></tr>";
            }
            echo "</table>";
        } else {
            echo "No hay alumnos."; 
        }

        // Calcular el número total de páginas
        $totalAlumnosResult = mysqli_query($conexion, "SELECT COUNT(*) as total FROM tbl_alumnos");
        if (!$totalAlumnosResult) {
            throw new Exception("Error al contar los alumnos: " . mysqli_error($conexion));
        }

        $totalAlumnos = mysqli_fetch_assoc($totalAlumnosResult)['total'];
        $totalPaginas = ceil($totalAlumnos / $alumnosPorPagina);

        // Navegación de páginas
        echo "<div class='pagination'>";
        for ($i = 1; $i <= $totalPaginas; $i++) {
            if ($i == $paginaActual) {
                echo "<strong>$i</strong> ";
            } else {
                echo "<a href='?pagina=$i&alumnosPorPagina=$alumnosPorPagina&nombre=$nombreFiltro&apellido=$apellidoFiltro'>$i</a> ";
            }
        }
        echo "</div>";

    } catch (Exception $e) {
        echo "Se produjo un error: " . $e->getMessage();
    }
    ?>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.14.5/dist/sweetalert2.all.min.js" integrity="sha256-1m4qVbsdcSU19tulVTbeQReg0BjZiW6yGffnlr/NJu4=" crossorigin="anonymous"></script>
    <!-- Modal de confirmación -->
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmDeleteModalLabel">Confirmar Eliminación</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    ¿Estás seguro de que deseas eliminar este alumno?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <a href="#" id="confirmDeleteButton" class="btn btn-danger">Eliminar</a>
                </div>
            </div>
        </div>
    </div>
    <script>
    $(document).ready(function() {
        $('.delete-link').on('click', function() {
            var id = $(this).data('id');
            $('#confirmDeleteButton').attr('href', 'eliminarAlumno.php?id=' + id);
        });
    });
    // Hace saltar el sweet alert de acceso conseguido.
    if(typeof loginSucces !== 'undefined' && loginSucces){
        swal.fire({
            title: 'Sesion iniciada',
            text: 'Bienvenido ' + user + '!',
            icon:'success',
        })
    }
    // Hace saltar el sweet alert de nota subida exitosamente
    if(typeof notaSubida !== 'undefined' && notaSubida){
        swal.fire({
            title: 'Nota subida',
            text: 'Nota subida exitosamente',
            icon: 'success'
        })
    }
    // Hace saltar el sweet alert de un usuario editado perfectamente
    if(typeof editarAlumno !== 'undefined' && editarAlumno){
        swal.fire({
            title: 'Alumno editado',
            text: 'Usuario editado exitosamente',
            icon:'success'
        })
    }
    // Hace saltar el sweet alert de un usuario eliminado perfectamente
    if(typeof eliminarAlumno !== 'undefined' && eliminarAlumno){
        swal.fire({
            title: 'Alumno eliminado',
            text: 'Alumno eliminado exitosamente',
            icon:'success'
        })
    }
    
</script>
</body>
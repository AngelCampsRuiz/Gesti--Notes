<?php
    session_start();
    if(!isset($_SESSION['id_usu'])){
        header('Location: ../index.php');
        exit();
    }
    // Hace que salte el sweet alert de acceso correcto solo una vez
    if(isset($_SESSION['loginTrue']) && $_SESSION['loginTrue']){
        unset($_SESSION['loginTrue']);
        $user = $_SESSION['username'];
        echo "<script> let loginSucces = true; let user = '$user';</script>";
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
</body>
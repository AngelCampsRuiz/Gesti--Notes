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
    <h2 id="iniciar-sesion">Gestion de Usuarios</h2>
    <?php
    try {
        // Incluir el archivo de conexión
        include '../database/conexion.php';
        checkMysqliError($conexion);

        // Obtener el número de alumnos por página
        $alumnosPorPagina = isset($_GET['alumnosPorPagina']) ? (int)$_GET['alumnosPorPagina'] : 10;
        $paginaActual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
        $offset = ($paginaActual - 1) * $alumnosPorPagina;

        // Obtener filtros
        $nombreFiltro = isset($_GET['nombre']) ? $_GET['nombre'] : '';
        $apellidoFiltro = isset($_GET['apellido']) ? $_GET['apellido'] : '';

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
?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.14.5/dist/sweetalert2.min.css" integrity="sha256-qWVM38RAVYHA4W8TAlDdszO1hRaAq0ME7y2e9aab354=" crossorigin="anonymous">
        <link rel="stylesheet" href="../css/styles.css">
        <title>Document</title>
    </head>
<?php
    // Formulario de filtrado
    ?>
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Logo</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="#">Home</a>
                    </li>
                    <li class="nav-item">
                        <form class="d-flex align-items-center" role="search" method="GET" action="">
                            <label class="nav-link active" aria-current="page">Nombre:</label>
                            <input class="form-control form-control-sm me-2" type="search" name="nombre" placeholder="Introduce un nombre" aria-label="Search" value="<?php echo htmlspecialchars($nombreFiltro); ?>">
                            <label class="nav-link active" aria-current="page">Apellido:</label>
                            <input class="form-control form-control-sm me-2" type="search" name="apellido" placeholder="Introduce un apellido" aria-label="Search" value="<?php echo htmlspecialchars($apellidoFiltro); ?>">
                            <button class="btn btn-outline-success btn-sm" type="submit">Buscar</button>
                        </form>
                    </li>
                    <?php
                        echo isset($_GET['nombre']) || isset($_GET['apellido']) ? "<li class='nav-item'>
                            <a class='nav-link active' href='gestionUsers.php'>Borrar Filtros</a>
                        </li>" : "";
                    ?>
                </ul>
                <a href="cerrarSesion.php"><button class='btn btn-danger btn-sm'>Cerrar Sesion</button></a>
            </div>
        </div>
    </nav>
    <h1>Estudiantes</h1>
    <!-- Botón para crear un nuevo alumno -->
    <a href="crearAlumno.php"><button class="btn btn-success btn-sm">Crear Nuevo Alumno</button></a>
    <a href="vistaNotas.php"><button class="btn btn-primary btn-sm">Notas De Alumnos</button></a>
    <?php
    // Mostrar los alumnos
    if (mysqli_num_rows($result) > 0) {
        echo "<table><tr><th>Nombre</th><th>Apellido</th><th>Email</th><th>Acciones</th></tr>";
        while($row = mysqli_fetch_assoc($result)) {
            $id = $row['id_alu'];
            $nombre = $row['nombre_alu'];
            echo "<tr><td><a href='notaAlumno.php?id={$id}'>$nombre</a></td><td>{$row['apellido_alu']}</td><td>{$row['email_alu']}</td>";
            echo "<td><a href='editarAlumno.php?id={$row['id_alu']}' class='btn btn-warning btn-sm'>Editar</a> | ";
            echo "<a href='#' class='btn btn-danger btn-sm delete-link' data-id='{$row['id_alu']}' data-toggle='modal' data-target='#confirmDeleteModal'>Eliminar</a></td></tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No hay alumnos.</p>"; 
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
if(typeof loginSucces !== 'undefined' && loginSucces){
    swal.fire({
        title: 'Sesion iniciada',
        text: 'Bienvenido ' + user + '!',
        icon:'success',
    })
}
</script>

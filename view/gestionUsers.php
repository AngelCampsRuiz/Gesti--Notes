<?php
function checkMysqliError($conexion) {
    if (mysqli_connect_errno()) {
        throw new Exception("Error de conexión: " . mysqli_connect_error());
    }
}

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

    // Formulario de filtrado
    ?>
    <form method="get">
        Nombre: <input type="text" name="nombre" value="<?php echo htmlspecialchars($nombreFiltro); ?>">
        Apellido: <input type="text" name="apellido" value="<?php echo htmlspecialchars($apellidoFiltro); ?>">
        <input type="submit" value="Filtrar">
    </form>
    <?php
    // Mostrar los alumnos
    if (mysqli_num_rows($result) > 0) {
        echo "<table><tr><th>Nombre</th><th>Apellido</th><th>Email</th><th>Acciones</th></tr>";
        while($row = mysqli_fetch_assoc($result)) {
            echo "<tr><td>{$row['nombre_alu']}</td><td>{$row['apellido_alu']}</td><td>{$row['email_alu']}</td>";
            echo "<td><a href='editarAlumno.php?id={$row['id_alu']}'>Editar</a> | <a href='eliminarAlumno.php?id={$row['id_alu']}'>Eliminar</a></td></tr>";
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

    // Mostrar enlaces de paginación
    for ($i = 1; $i <= $totalPaginas; $i++) {
        echo "<a href='?pagina=$i&alumnosPorPagina=$alumnosPorPagina'>$i</a> ";
    }

    mysqli_close($conexion);

} catch (Exception $e) {
    echo "Se produjo un error: " . $e->getMessage();
}
?>

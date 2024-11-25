<?php
require_once 'conexion.php';

if (isset($_GET['asignatura'])) {
    try {
        // Consulta para obtener el promedio, el mejor alumno y la mejor nota por asignatura
        $sqlMediaPorAsignatura = "SELECT 
                                    a.nombre_asig,
                                    AVG(n.nota_alu) AS promedio,
                                    (
                                        SELECT CONCAT(l.nombre_alu, ' ', l.apellido_alu)
                                        FROM tbl_notas n2
                                        INNER JOIN tbl_alumnos l ON n2.id_alu = l.id_alu
                                        WHERE n2.id_asig = a.id_asig
                                        ORDER BY n2.nota_alu DESC
                                        LIMIT 1
                                    ) AS nombre_alu,
                                    (
                                        SELECT MAX(n3.nota_alu)
                                        FROM tbl_notas n3
                                        WHERE n3.id_asig = a.id_asig
                                    ) AS nota_alu
                                    FROM tbl_asignatura a
                                    LEFT JOIN tbl_notas n ON a.id_asig = n.id_asig
                                    GROUP BY a.id_asig
                                    ORDER BY promedio DESC";
        $stmtMediaPorAsignatura = mysqli_stmt_init($conn);
        mysqli_stmt_prepare($stmtMediaPorAsignatura, $sqlMediaPorAsignatura);
        mysqli_stmt_execute($stmtMediaPorAsignatura);
        $resultNotasAlumnos = mysqli_stmt_get_result(statement: $stmtMediaPorAsignatura);
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
        exit();
    }
}
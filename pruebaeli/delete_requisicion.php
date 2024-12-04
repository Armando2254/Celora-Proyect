<?php
include '../conexion_bd.php';

$id = $_GET['id'];

// Paso 1: Eliminar el reporte de la tabla report
$deleteReportQuery = "DELETE FROM report WHERE requisitionId = ?";

// Preparar la sentencia
$stmt = mysqli_prepare($conexion, $deleteReportQuery);

// Comprobar si la preparación fue exitosa
if ($stmt === false) {
    echo "Error al preparar la consulta para eliminar reporte: " . mysqli_error($conexion);
    exit;
}

// Vincular el parámetro
mysqli_stmt_bind_param($stmt, "i", $id);

// Ejecutar la sentencia
if (!mysqli_stmt_execute($stmt)) {
    echo "Error al ejecutar la consulta de eliminación de reporte: " . mysqli_error($conexion);
    exit;
}

// Cerrar la sentencia del reporte
mysqli_stmt_close($stmt);

// Paso 2: Eliminar los registros relacionados en requisition
$deleteRequisitionQuery = "DELETE FROM requisition WHERE id = ?";
$stmt2 = mysqli_prepare($conexion, $deleteRequisitionQuery);

// Comprobar si la preparación fue exitosa
if ($stmt2 === false) {
    echo "Error al preparar la consulta para eliminar requisición: " . mysqli_error($conexion);
    exit;
}

// Vincular el parámetro
mysqli_stmt_bind_param($stmt2, "i", $id);

// Ejecutar la sentencia
if (!mysqli_stmt_execute($stmt2)) {
    echo "Error al ejecutar la consulta de eliminación de requisición: " . mysqli_error($conexion);
    exit;
}

// Cerrar la sentencia de requisición
mysqli_stmt_close($stmt2);



// Si todo fue exitoso
echo "Reporte y registros relacionados eliminados correctamente.";

?>

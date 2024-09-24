<?php
header('Content-Type: application/json');

// Incluir el archivo de conexión
require '../BD/conexiondi.php';

// Obtener el ID del programa de la solicitud
$userId = isset($_POST['userId']) ? intval($_POST['userId']) : 0;
$programId = isset($_POST['programId']) ? intval($_POST['programId']) : 0;

// Obtener la conexión a la base de datos
$conn = conectar();


error_log("usuario: ".$userId." programa: ".$programId); 

// Verificar si la conexión fue exitosa
if ($conn === false) {
    die('Error de conexión: ' . mysqli_connect_error());
}

// Consulta para obtener los semestres
$query = "
    SELECT DISTINCT ccc.id, ccc.name
FROM mdl_course_categories ccc
JOIN mdl_course c ON c.category = ccc.id
JOIN mdl_enrol e ON e.courseid = c.id
JOIN mdl_user_enrolments ue ON ue.enrolid = e.id
WHERE ue.userid = ?
  AND ccc.parent = ?
  AND ccc.visible = 1
  AND ccc.name LIKE '%semestre%'
ORDER BY ccc.name
";

// Preparar la consulta
$stmt = $conn->prepare($query);

// Verificar si la preparación de la consulta falló
if ($stmt === false) {
    die('Error en la preparación de la consulta: ' . $conn->error);
}

// Vincular los parámetros
$stmt->bind_param('ii',  $userId,$programId);

// Ejecutar la consulta
$stmt->execute();
$result = $stmt->get_result();

// Recuperar los datos
$semesters = array();
while ($row = $result->fetch_assoc()) {
    $semesters[] = $row;
}

// Devolver los datos en formato JSON
echo json_encode($semesters);

// Cerrar la sentencia y la conexión
$stmt->close();
$conn->close();
?>

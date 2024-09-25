<?php
header('Content-Type: application/json');

// Incluir el archivo de conexión
require '../BD/conexiondi.php';

// Obtener el ID del semestre de la solicitud
$userId = isset($_POST['userId']) ? intval($_POST['userId']) : 0;
$semesterId = isset($_POST['semesterId']) ? intval($_POST['semesterId']) : 0;

// Obtener la conexión a la base de datos
$conn = conectar();

// Verificar si la conexión fue exitosa
if ($conn === false) {
    die('Error de conexión: ' . mysqli_connect_error());
}

// Consulta para obtener los cursos
$query = "
    	SELECT DISTINCT c.id, c.fullname, c.shortname, c.startdate, c.enddate
FROM mdl_course AS c
INNER JOIN mdl_course_categories AS cc ON c.category = cc.id
INNER JOIN mdl_course_categories AS ccc ON ccc.id = cc.parent
INNER JOIN mdl_enrol e ON e.courseid = c.id
INNER JOIN mdl_user_enrolments ue ON ue.enrolid = e.id
WHERE c.visible = 1 
  AND c.category = ? 
  AND ue.userid = ? ;
";

// Preparar la consulta
$stmt = $conn->prepare($query);

// Verificar si la preparación de la consulta falló
if ($stmt === false) {
    die('Error en la preparación de la consulta: ' . $conn->error);
}

// Vincular los parámetros
$stmt->bind_param('ii', $semesterId, $userId);

// Ejecutar la consulta
$stmt->execute();
$result = $stmt->get_result();

// Recuperar los datos
$courses = array();
while ($row = $result->fetch_assoc()) {
    $courses[] = $row;
}

// Devolver los datos en formato JSON
echo json_encode($courses);

// Cerrar la sentencia y la conexión
$stmt->close();
$conn->close();
?>

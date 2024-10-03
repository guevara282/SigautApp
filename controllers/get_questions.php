<?php
header('Content-Type: application/json');

// Incluir el archivo de conexión
require '../BD/conexiondi.php';

// Obtener el ID del programa de la solicitud
$userId = isset($_POST['userId']) ? intval($_POST['userId']) : 0;

// Obtener la conexión a la base de datos
$conn = conectar();


//error_log("usuario: ".$userId." programa: ".$programId); 

// Verificar si la conexión fue exitosa
if ($conn === false) {
    die('Error de conexión: ' . mysqli_connect_error());
}

// Consulta para obtener los semestres
$query = "
   SELECT sgcategoria.PROGRAMA, sgcategoria.SEMESTRE, sgcategoria.GRUPO, sgpreguntas.ENUNCIADO, sgpreguntas.TIPOPREGUNTA, sgpreguntas.ID  
   FROM sgpreguntas 
   JOIN sgcategoria ON sgcategoria.ID = sgpreguntas.CATEGORIA
   WHERE sgpreguntas.USUARIOID=?
";


// Preparar la consulta
$stmt = $conn->prepare($query);

// Verificar si la preparación de la consulta falló
if ($stmt === false) {
    die('Error en la preparación de la consulta: ' . $conn->error);
}

// Vincular los parámetros
$stmt->bind_param('i',  $userId);

// Ejecutar la consulta
$stmt->execute();
$result = $stmt->get_result();

// Recuperar los datos
$questions = array();
while ($row = $result->fetch_assoc()) {
    $questions[] = $row;
}

// Devolver los datos en formato JSON
echo json_encode($questions);

// Cerrar la sentencia y la conexión
$stmt->close();
$conn->close();
?>

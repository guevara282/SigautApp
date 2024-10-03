<?php
// Incluir el archivo de conexión
require '../BD/conexiondi.php';

// Obtener la conexión
$conn = conectar();

// Aquí debes tener la conexión a la base de datos
if (isset($_GET['id'])) {
    $questionId = $_GET['id'];

    // Aquí debes ajustar la consulta según tu base de datos
    $query = "SELECT * FROM sgpreguntas WHERE ID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $questionId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $question = $result->fetch_assoc();
        echo json_encode($question);
    } else {
        echo json_encode([]);
    }

    $stmt->close();
}
?>

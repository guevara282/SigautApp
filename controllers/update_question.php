<?php
require_once('../BD/conexiondi.php');

// Conectar a la base de datos
$conn = conectar();

function buscaridmoodle($conn, $questionId)
{
    $sql = "SELECT ID_PREGUNTAMOODLE FROM sgpreguntas WHERE id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die("Error en la consulta SQL: " . $conn->error);
    }

    $stmt->bind_param("s", $questionId);

    if ($stmt->execute()) {
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Obtener la fila de resultados
            $row = $result->fetch_assoc();
            $stmt->close();
            return $row['ID_PREGUNTAMOODLE']; // Retornar el valor real de la columna
        } else {
            $stmt->close();
            return null; // No se encontró ningún registro
        }
    } else {
        echo "Error al buscar en la base de datos: " . $stmt->error;
        $stmt->close();
        return false;
    }
}

// Verificar si se ha enviado la solicitud POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recoger los datos enviados desde el formulario
    $questionId = $_POST['questionId'];
    $courseType = $_POST['courseType'];
    $typequestion = $_POST['typequestion'];
    $question = $_POST['question'];
    $correctOption = $_POST['correctOption'];
    $option2 = $_POST['option2'];
    $option3 = $_POST['option3'];
    $option4 = $_POST['option4'];
    $affirmation = $_POST['affirmation'];
    $justification = $_POST['justification'];
    $evidence = $_POST['evidence'];
    $fecha = date('ymd');

    $idpreguntamoodle = buscaridmoodle($conn, $questionId);
    error_log("idmoodle: " . $idpreguntamoodle);
    error_log("questionId: " . $questionId);
    error_log("courseType: " . $courseType);
    error_log("typequestion: " . $typequestion);
    error_log("question: " . $question);
    error_log("correctOption: " . $correctOption);
    error_log("option2: " . $option2);
    error_log("option3: " . $option3);
    error_log("option4: " . $option4);
    error_log("affirmation: " . $affirmation);
    error_log("justification: " . $justification);
    error_log("evidence: " . $evidence);
    error_log("fecha: " . $fecha);


    if ($courseType == "Abierta") {

        $idpreguntamoodlenuevo = preg_replace('/(M1)(.*?)(\-)/', 'M1' . $typequestion . '-', $idpreguntamoodle);


        $preguntaabiertabase = '::"IDPREGUNTAMOODLE"::;[html]<p>"QUESTION"\:</p>{;};';

        // Reemplazar los placeholders con los valores ingresados
        $preguntaabierta = str_replace(
            ['"IDPREGUNTAMOODLE"', '"QUESTION"'],
            [$idpreguntamoodlenuevo, $question],
            $preguntaabiertabase
        );



        // Preparar la consulta para actualizar la pregunta
        $query = "UPDATE sgpreguntas 
      SET ENUNCIADO = ?, ID_PREGUNTAMOODLE=?, PREGUNTACOMPLETA =? ,TIPOPREGUNTA=?
      WHERE ID = ?";

        $stmt = $conn->prepare($query);
        $stmt->bind_param(
            'ssssi',
            $question,
            $idpreguntamoodlenuevo,
            $preguntaabierta,
            $typequestion,
            $questionId
        );

        if ($stmt->execute()) {
            // Respuesta exitosa en formato JSON
            echo json_encode(['success' => true]);
        } else {
            // Error al ejecutar la consulta
            echo json_encode(['success' => false]);
        }

        $stmt->close();
        $conn->close();
        error_log("pregunta abierta " . $idpreguntamoodlenuevo . " pregunta nueva " . $preguntaabierta);
    } elseif ($courseType == "Seleccion multiple") {

        $idpreguntamoodlenuevo = preg_replace('/(M1)(.*?)(\-)/', 'M1' . $typequestion . '-', $idpreguntamoodle);

        $preguntabase = '::"IDPREGUNTAMOODLE"::;[html]<p>"QUESTION"\:</p>{;=<p>"CORRECTOPTION"</p>;~<p>"OPTION2"</p>;~<p>"OPTION3"</p>;~<p>"OPTION4"</p>;####<p>Afirmación\:"AFFIRMATION";<p>Justificación\:"JUSTIFICATION"</p>;<p>Evidencia\:"EVIDENCE"</p>};';

        // Reemplazar los placeholders con los valores ingresados
        $preguntacompleta = str_replace(
            ['"IDPREGUNTAMOODLE"', '"QUESTION"', '"CORRECTOPTION"', '"OPTION2"', '"OPTION3"', '"OPTION4"', '"AFFIRMATION"', '"JUSTIFICATION"', '"EVIDENCE"'],
            [$idpreguntamoodlenuevo, $question, $correctOption, $option2, $option3, $option4, $affirmation, $justification, $evidence],
            $preguntabase

        );


        // Preparar la consulta para actualizar la pregunta
        $query = "UPDATE sgpreguntas 
      SET ID_PREGUNTAMOODLE=?, ENUNCIADO=?, OPCIONCORRECTA=?, OPCIONB=?, OPCIONC=?, OPCIOND=?, AFIRMACION=?, JUSTIFICACION=?, EVIDENCIA=?, PREGUNTACOMPLETA=?, TIPOPREGUNTA=? 
      WHERE ID = ?";


        $stmt = $conn->prepare($query);
        $stmt->bind_param(
            'sssssssssssi',
            $idpreguntamoodlenuevo,
            $question,
            $correctOption,
            $option2,
            $option3,
            $option4,
            $affirmation,
            $justification,
            $evidence,
            $preguntacompleta,
            $typequestion,
            $questionId
        );

        if ($stmt->execute()) {
            // Respuesta exitosa en formato JSON
            echo json_encode(['success' => true]);
        } else {
            // Error al ejecutar la consulta
            echo json_encode(['success' => false]);
        }

        $stmt->close();
        $conn->close();
        error_log("pregunta abierta " . $idpreguntamoodlenuevo . " pregunta nueva ");
    }





    /*

    $preguntabase = '::"FECHA"M1"TYPECUESTION""CANTPREGUNTAS"-"PROGRAMACORTO""SEMESTRECORTO""CURSOCORTO"::;[html]<p>"QUESTION"\:</p>{;=<p>"CORRECTOPTION"</p>;~<p>"OPTION2"</p>;~<p>"OPTION3"</p>;~<p>"OPTION4"</p>;####<p>Afirmación\:"AFFIRMATION";<p>Justificación\:"JUSTIFICATION"</p>;<p>Evidencia\:"EVIDENCE"</p>};';

    // Reemplazar los placeholders con los valores ingresados
    $preguntacompleta = str_replace(
        ['"FECHA"', '"TYPECUESTION"', '"CANTPREGUNTAS"', '"PROGRAMACORTO"', '"SEMESTRECORTO"', '"CURSOCORTO"', '"QUESTION"', '"CORRECTOPTION"', '"OPTION2"', '"OPTION3"', '"OPTION4"', '"AFFIRMATION"', '"JUSTIFICATION"', '"EVIDENCE"'],
        [$fecha, $typequestion, $totalPreguntas, $programacorto, $semestrecorto, $cursocorto, $question, $correctOption, $option2, $option3, $option4, $affirmation, $justification, $evidence],
        $preguntabase
    );

    $preguntaabiertabase = '::"FECHA"M1"TYPECUESTION""CANTPREGUNTAS"-"PROGRAMACORTO""SEMESTRECORTO""CURSOCORTO"::;[html]<p>"QUESTION"\:</p>{;};';

    // Reemplazar los placeholders con los valores ingresados
    $preguntaabierta = str_replace(
        ['"FECHA"', '"TYPECUESTION"', '"CANTPREGUNTAS"', '"PROGRAMACORTO"', '"SEMESTRECORTO"', '"CURSOCORTO"', '"QUESTION"'],
        [$fecha, $typequestion, $totalPreguntas, $programacorto, $semestrecorto, $cursocorto, $question],
        $preguntaabiertabase
    );


    // Preparar la consulta para actualizar la pregunta
    $query = "UPDATE sgpreguntas 
              SET ENUNCIADO = ?, OPCIONCORRECTA = ?, OPCIONB = ?, OPCIONC = ?, OPCIOND = ?, 
                  AFIRMACION = ?, JUSTIFICACION = ?, EVIDENCIA = ? 
              WHERE ID = ?";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ssssssssi', $question, $correctOption, $option2, $option3, $option4, 
                      $affirmation, $justification, $evidence, $questionId);

    if ($stmt->execute()) {
        // Respuesta exitosa en formato JSON
        echo json_encode(['success' => true]);
    } else {
        // Error al ejecutar la consulta
        echo json_encode(['success' => false]);
    }

    $stmt->close();
    $conn->close();*/
}

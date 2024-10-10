<?php
include '../BD/conexiondi.php';


// Función para buscar el texto en la tabla 'categoria'
function buscarCategoria($conn, $categorycompleto)
{
    $sql = "SELECT * FROM `di`.`sgcategoria` WHERE indicecompleto LIKE ?";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die("Error en la consulta SQL: " . $conn->error);
    }

    $search = "%" . $categorycompleto . "%"; // Agregar comodines
    $stmt->bind_param("s", $search);

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $stmt->close();
        return $result;
    } else {
        echo "Error al buscar en la base de datos: " . $stmt->error;
        $stmt->close();
        return false;
    }
}

//funcion para buscar una pregunta cognitiva 

// Función para buscar el texto en la tabla 'preguntas'
function buscarPregunta($conn, $categoryid)
{
    $sql = "SELECT tipopregunta, COUNT(*) AS total FROM sgpreguntas WHERE categoria = ? GROUP BY tipopregunta";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die("Error en la consulta SQL: " . $conn->error);
    }

    $stmt->bind_param("s", $categoryid);

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $stmt->close();
        return $result;
    } else {
        echo "Error al buscar en la base de datos: " . $stmt->error;
        $stmt->close();
        return false;
    }
}

// Función para insertar una nueva categoría en la tabla 'categoria'
function insertarCategoria($conn, $categoria, $programa, $semester, $course, $categorycompleto)
{
    $sql = "INSERT INTO `di`.`sgcategoria` (`categoria`, `programa`, `semestre`, `grupo`, `indicecompleto`) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die("Error en la consulta SQL: " . $conn->error);
    }

    $stmt->bind_param("sssss", $categoria, $programa, $semester, $course, $categorycompleto);

    if ($stmt->execute()) {
        $stmt->close();
        return true;
    } else {
        echo "Error al insertar en la base de datos: " . $stmt->error;
        $stmt->close();
        return false;
    }
}
function insertarPregunta($conn, $ID_PREGUNTAMOODLE, $question, $correctOption, $option2, $option3, $option4, $affirmation, $justification, $evidence, $preguntacompleta, $id_categoria, $usuario, $typequestion, $userid)
{
    $sql = "INSERT INTO `di`.`sgpreguntas` (`ID_PREGUNTAMOODLE`, `enunciado`, `opcioncorrecta`, `opcionb`, `opcionc`, `opciond`, `afirmacion`, `justificacion`, `evidencia`, `preguntacompleta`,`categoria`,`usuario`,`tipopregunta`,`usuarioid`  ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?,?, ?,?,?,?)";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die("Error en la consulta SQL: " . $conn->error);
    }

    $stmt->bind_param("sssssssssssssi", $ID_PREGUNTAMOODLE, $question, $correctOption, $option2, $option3, $option4, $affirmation, $justification, $evidence, $preguntacompleta, $id_categoria, $usuario, $typequestion, $userid);

    if ($stmt->execute()) {
        $stmt->close();
        return true;
    } else {
        echo "Error al insertar en la base de datos: " . $stmt->error;
        $stmt->close();
        return false;
    }
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Verificar si el usuario de Moodle está presente

    try {
        $add=false;
        // Separar el programa y el código usando explode
        list($programa, $codigoprograma) = explode(',', $_POST['program']);
        list($semester, $codigosemestre) = explode(',', $_POST['semester']);
        // Obtener los valores ingresados en el formulario
        $categoria = '$CATEGORY: $system$/A 2024 - 02/Indice/';
        // $programa = $_POST['program'];
        $course = $_POST['course'];
        // $semester = $_POST['semester'];
        $question = $_POST['question'];
        $option2 = $_POST['option2'] ?? '';  // Valor por defecto si no existe
        $option3 = $_POST['option3'] ?? '';  // Valor por defecto si no existe
        $correctOption = $_POST['correctOption'] ?? Null;  // Valor por defecto si no existe
        $option4 = $_POST['option4'] ?? '';  // Valor por defecto si no existe
        $affirmation = $_POST['affirmation'] ?? '';  // Valor por defecto si no existe
        $justification = $_POST['justification'] ?? '';  // Valor por defecto si no existe
        $evidence = $_POST['evidence'] ?? '';  // Valor por defecto si no existe
        $fecha = date('ymd');
        $cursocorto = str_replace(' ', '', $course);
        $programacorto = "";
        $semestrecorto = 'S' . str_replace('Semestre ', '', $semester);;
        $ID_PREGUNTAMOODLE = "";
        $usuario = $_POST['user'];
        $userid = $_POST['userid'];
        $typequestion =  $_POST['typequestion'];
    } catch (\Throwable $th) {
    }


    if ($programa == "Licenciatura en Educación Infantil") {
        $programacorto = "LEI";
    } elseif ($programa == "Criminalística") {
        $programacorto = "TEC";
    } elseif ($programa == "Adminitracion Financiera") {
        $programacorto = "AF";
    } elseif ($programa == "Mercadeo") {
        $programacorto = "M";
    } elseif ($programa == "Licenciatura en Pedagogia Infantil") {
        $programacorto = "LPI";
    }

    $conn = conectar();




    $categorybase = '$CATEGORY: $system$/A 2024 - 02/Indice/"PROGRAMA"/"SEMESTER"/"COURSE"/M1';
    $categorycompleto = str_replace(
        ['"PROGRAMA"', '"SEMESTER"', '"COURSE"'],
        [$programa, $semester, $course],
        $categorybase
    );
    // Buscar categoría
    $result = buscarCategoria($conn, $categorycompleto);
    $row = $result->fetch_assoc();
    $cant_preguntasint = buscarPregunta($conn, $row['ID']);
    $totalPreguntas = 1;
    //$cant_preguntas = 0;
    $cant_preguntasC = 0;
    $cant_preguntasM = 0;
   // echo "cant preguntas: " . $cant_preguntas;
    while ($row3 = $cant_preguntasint->fetch_assoc()) {
        $tipoPregunta = $row3['tipopregunta'];
        $total = $row3['total'];
        if ($tipoPregunta == "C") {
            $cant_preguntasC = $total;
            
           // echo "cantidad c " . $total;
        } elseif ($tipoPregunta == "M") {
            $cant_preguntasM = $total;
           // echo "cantidad m " . $total;
        }

        if($cant_preguntasC==Null){
            $cant_preguntasC=0;
        }else if($cant_preguntasM==Null){
            $cant_preguntasM=0;
        }
      //  echo "Tipo de Pregunta: " . $tipoPregunta . " - Total: " . $total . "<br>";
        // Sumar el total de preguntas de esta categoría al contador global
        $totalPreguntas += $total;
    }

    // Mostrar el total de preguntas de todas las categorías
   // echo "Total de preguntas en todas las categorías: " . $totalPreguntas;



    $preguntabase = '::"FECHA"M1"TYPECUESTION"-"PROGRAMACORTO""SEMESTRECORTO""CURSOCORTO"::;[html]<p>"QUESTION"\:</p>{;=<p>"CORRECTOPTION"</p>;~<p>"OPTION2"</p>;~<p>"OPTION3"</p>;~<p>"OPTION4"</p>;####<p>Afirmación\:"AFFIRMATION";<p>Justificación\:"JUSTIFICATION"</p>;<p>Evidencia\:"EVIDENCE"</p>};';

    // Reemplazar los placeholders con los valores ingresados
    $preguntacompleta = str_replace(
        ['"FECHA"', '"TYPECUESTION"', '"PROGRAMACORTO"', '"SEMESTRECORTO"', '"CURSOCORTO"', '"QUESTION"', '"CORRECTOPTION"', '"OPTION2"', '"OPTION3"', '"OPTION4"', '"AFFIRMATION"', '"JUSTIFICATION"', '"EVIDENCE"'],
        [$fecha, $typequestion, $programacorto, $semestrecorto, $cursocorto, $question, $correctOption, $option2, $option3, $option4, $affirmation, $justification, $evidence],
        $preguntabase
    );

    $preguntaabiertabase = '::"FECHA"M1"TYPECUESTION"-"PROGRAMACORTO""SEMESTRECORTO""CURSOCORTO"::;[html]<p>"QUESTION"\:</p>{;};';

    // Reemplazar los placeholders con los valores ingresados
    $preguntaabierta = str_replace(
        ['"FECHA"', '"TYPECUESTION"', '"PROGRAMACORTO"', '"SEMESTRECORTO"', '"CURSOCORTO"', '"QUESTION"'],
        [$fecha, $typequestion, $programacorto, $semestrecorto, $cursocorto, $question],
        $preguntaabiertabase
    );




    $id_base = '"FECHA"M1"TYPECUESTION"-"PROGRAMACORTO""SEMESTRECORTO""CURSOCORTO"';
    $ID_PREGUNTAMOODLE = str_replace(
        ['"FECHA"', '"TYPECUESTION"', '"PROGRAMACORTO"', '"SEMESTRECORTO"', '"CURSOCORTO"'],
        [$fecha, $typequestion, $programacorto, $semestrecorto, $cursocorto],
        $id_base
    );
    // echo " idpregunta " . $ID_PREGUNTAMOODLE;


    if ($result && $result->num_rows > 0) {
      //  echo "existe categoria";
        if ($totalPreguntas < 11) {
            if ($typequestion == "C" && $cant_preguntasC < 8) {
                if ($correctOption == null) {
                    

                    insertarPregunta($conn, $ID_PREGUNTAMOODLE, $question, $correctOption, $option2, $option3, $option4, $affirmation, $justification, $evidence, $preguntaabierta, $row['ID'], $usuario, $typequestion, $userid);
                    //echo "Texto encontrado, te quedan " . $cant_preguntas . " preguntas ";
                    // echo "pregunta abierta";
                   
                } else {
                  
                    $resultpreguntas = insertarPregunta($conn, $ID_PREGUNTAMOODLE, $question, $correctOption, $option2, $option3, $option4, $affirmation, $justification, $evidence, $preguntacompleta, $row['ID'], $usuario, $typequestion, $userid);
                    //  echo "Texto encontrado, te quedan " . $cant_preguntas . " preguntas ";

                }
               $cant_preguntasC++;
               $add=true;
               
            } elseif ($typequestion == "M" && $cant_preguntasM < 2) {
                if ($correctOption == null) {
                   

                    insertarPregunta($conn, $ID_PREGUNTAMOODLE, $question, $correctOption, $option2, $option3, $option4, $affirmation, $justification, $evidence, $preguntaabierta, $row['ID'], $usuario, $typequestion, $userid);
                    //echo "Texto encontrado, te quedan " . $cant_preguntas . " preguntas ";
                    // echo "pregunta abierta";
                } else {
                   
                    $resultpreguntas = insertarPregunta($conn, $ID_PREGUNTAMOODLE, $question, $correctOption, $option2, $option3, $option4, $affirmation, $justification, $evidence, $preguntacompleta, $row['ID'], $usuario, $typequestion, $userid);
                    //  echo "Texto encontrado, te quedan " . $cant_preguntas . " preguntas ";
                }
                
               $cant_preguntasM++;
               $add=true;
            }
            
        } else {
            // echo "preguntas completas para esta categoria";
        }
    } else {
        //echo "no existe categoria";
        // Insertar categoría si no se encontró
        if (insertarCategoria($conn, $categoria, $programa, $semester, $course, $categorycompleto)) {
            // echo "No se encontraron resultados para: " . $categorycompleto . ", se ha insertado un nuevo registro.";
            $result =  buscarCategoria($conn, $categorycompleto);
            $row = $result->fetch_assoc();
          //  $cant_preguntas = 9;
            if ($correctOption == null) {
               if($typequestion == "C"){
                $cant_preguntasC++;
               }else{
                $cant_preguntasM++;
               }
                $preguntacompleta = "";
                insertarPregunta($conn, $ID_PREGUNTAMOODLE, $question, $correctOption, $option2, $option3, $option4, $affirmation, $justification, $evidence, $preguntaabierta, $row['ID'], $usuario, $typequestion, $userid);
                //echo "Texto encontrado, te quedan " . $cant_preguntas . " preguntas ";
                // echo "pregunta abierta";
                $add=true;
            } else {
                if($typequestion == "C"){
                    $cant_preguntasC++;
                   }else{
                    $cant_preguntasM++;
                   }
                $resultpreguntas = insertarPregunta($conn, $ID_PREGUNTAMOODLE, $question, $correctOption, $option2, $option3, $option4, $affirmation, $justification, $evidence, $preguntacompleta, $row['ID'], $usuario, $typequestion, $userid);
                //  echo "Texto encontrado, te quedan " . $cant_preguntas . " preguntas ";
                $add=true;
            }
        }
    }

    $conn->close();
    //echo " preguntas :". $cant_preguntasC.'mmmmm'.$cant_preguntasM;
 
   /*echo "<script>
              localStorage.setItem('preguntas_restantesC', $cant_preguntasC);
              localStorage.setItem('preguntas_restantesM', $cant_preguntasM);
              window.location.href = '../index.php';
           </script>";
    exit();*/
        // Responder con JSON
        if($add==true){
            echo json_encode([
                'success' => true,
                'message' => 'Pregunta agregada con éxito',
                'preguntas_restantesC' => $cant_preguntasC,
                'preguntas_restantesM' => $cant_preguntasM
            ]);
            exit();
        }else{
            echo json_encode([
                'success' => false,
                'message' => 'Pregunta agregada con éxito',
                'preguntas_restantesC' => $cant_preguntasC,
                'preguntas_restantesM' => $cant_preguntasM
            ]);
            exit();
        }
       
    
}

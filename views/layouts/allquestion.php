<?php
require_once('../../lib/auth_roles.php');
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Preguntas</title>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css">
    <style>
        /* Estilos del modal */
        .modal {
            display: none;
            /* Ocultar por defecto */
            position: fixed;
            z-index: 1000;
            /* Enviar al frente */
            left: 0;
            top: 0;
            width: 100%;
            /* Ancho completo */
            height: 100%;
            /* Alto completo */
            overflow: auto;
            /* Permitir desplazamiento si es necesario */
            background-color: rgba(0, 0, 0, 0.4);
            /* Fondo oscuro */
        }

        .modal-content {
            background-color: #fefefe;
            margin: 10% auto;
            /* Ajusta este valor para mover el modal más arriba */
            padding: 20px;
            border: 1px solid #888;
            border-radius: 10px;
            /* Bordes redondeados */
            width: 90%;
            /* Ancho del modal */
            max-width: 800px;
            /* Ancho máximo aumentado */
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
            /* Sombra */
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>

<body>
    <h1>Todas las preguntas</h1>
    <table id="questionsTable" class="display" style="width:100%">
        <thead>
            <tr>
                <th>Programa</th>
                <th>Semestre</th>
                <th>Grupo</th>
                <th>Enunciado</th>
                <th>Tipo de Pregunta</th>
                <th>Editar</th>
            </tr>
        </thead>
        <tbody id="questionsTableBody">
            <!-- Aquí se llenarán las filas dinámicamente -->
        </tbody>
    </table>

    <!-- Modal -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Editar Pregunta</h2>
            <form id="editQuestionForm">
                <div class="selectGroup mb-3">
                    <select id="courseType" name="courseType" onchange="toggleFields()" required>
                        <option value="" disabled selected>Selecciona el tipo de pregunta</option>
                        <option value="Seleccion multiple">Seleccion múltiple</option>
                        <option value="Abierta">Pregunta Abierta</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Tipo de Pregunta:</label>
                    <div class="container">
                        <label for="cognitive">
                            <input type="radio" id="cognitive" name="typequestion" value="C" required>
                            <span class="checkbox">Cognitiva</span>
                        </label>
                    </div>
                    <div class="container">
                        <label for="metacognitive">
                            <input type="radio" id="metacognitive" name="typequestion" value="M" required>
                            <span class="checkbox">Metacognitiva</span>
                        </label>
                    </div>
                </div>

                <div class="inputGroup">
                    <textarea
                        id="question"
                        name="question"
                        class="form-control"
                        rows="4"
                        required></textarea>
                    <label for="question">Pregunta:</label>
                </div>

                <div id="additionalFields" style="display: none;">
                    <div class="inputGroup">
                        <input
                            type="text"
                            id="correctOption"
                            name="correctOption"
                            required
                            autocomplete="off"
                            class="form-control" />
                        <label for="correctOption">Opción Correcta</label>
                    </div>

                    <div class="inputGroup">
                        <input
                            type="text"
                            id="option2"
                            name="option2"
                            autocomplete="off"
                            class="form-control" />
                        <label for="option2">Opción 2</label>
                    </div>

                    <div class="inputGroup">
                        <input
                            type="text"
                            id="option3"
                            name="option3"
                            autocomplete="off"
                            class="form-control" />
                        <label for="option3">Opción 3</label>
                    </div>

                    <div class="inputGroup">
                        <input
                            type="text"
                            id="option4"
                            name="option4"
                            autocomplete="off"
                            class="form-control" />
                        <label for="option4">Opción 4</label>
                    </div>

                    <div class="inputGroup">
                        <textarea
                            id="affirmation"
                            name="affirmation"
                            class="form-control"
                            rows="4"
                            autocomplete="off"></textarea>
                        <label for="affirmation">Afirmación</label>
                    </div>

                    <div class="inputGroup">
                        <textarea
                            id="justification"
                            name="justification"
                            class="form-control"
                            rows="4"
                            autocomplete="off"></textarea>
                        <label for="justification">Justificación</label>
                    </div>

                    <div class="inputGroup">
                        <textarea
                            id="evidence"
                            name="evidence"
                            class="form-control"
                            rows="4"
                            autocomplete="off"></textarea>
                        <label for="evidence">Evidencia</label>
                    </div>
                    <input type="hidden" id="questionId" name="questionId">

                </div>

                <button type="submit">Guardar</button>
            </form>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- DataTables JS -->
    <script type="text/javascript" src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
    <script src="./public/js/get_questions.js"></script>
    <script>
        $(document).ready(function() {
            // Llamar a la función para cargar las preguntas cuando la página esté lista
            get_questionsall();
        });

        function showEditModal(questionId) {
            console.log(questionId + "id en show modal");
            // Hacer una llamada AJAX para obtener los datos de la pregunta
            $.ajax({

                url: 'http://172.16.20.165/dist/SigautApp/controllers/get_questionsid.php',
                type: 'GET',
                data: {
                    id: questionId
                },
                dataType: 'json',
                success: function(question) {
                    console.log(question + "question");
                    if (question) {
                        // Establecer los valores de los campos del modal
                        //document.getElementById("typequestion").value= question.TIPOPREGUNTA;
                        document.getElementById("question").value = question.ENUNCIADO; // Asumiendo que tienes el enunciado
                        document.getElementById("correctOption").value = question.OPCIONCORRECTA; // Ajusta según tu columna
                        document.getElementById("option2").value = question.OPCIONB; // Ajusta según tu columna
                        document.getElementById("option3").value = question.OPCIONC; // Ajusta según tu columna
                        document.getElementById("option4").value = question.OPCIOND; // Ajusta según tu columna
                        document.getElementById("affirmation").value = question.AFIRMACION; // Ajusta según tu columna
                        document.getElementById("justification").value = question.JUSTIFICACION; // Ajusta según tu columna
                        document.getElementById("evidence").value = question.EVIDENCIA; // Ajusta según tu columna
                        document.getElementById("questionId").value = questionId;
                        // Cargar el tipo de pregunta (Cognitiva o Metacognitiva)
                        if (question.TIPOPREGUNTA === "C") {
                            document.getElementById("cognitive").checked = true;
                        } else if (question.TIPOPREGUNTA === "M") {
                            document.getElementById("metacognitive").checked = true;
                        }

                        // Cargar el tipo de curso (Seleccion múltiple o Pregunta Abierta)
                        document.getElementById("courseType").value = question.COURSETYPE;
                        // Mostrar el modal
                        var modal = document.getElementById("editModal");
                        modal.style.display = "block";
                    } else {
                        alert("No se encontró la pregunta.");
                    }
                },
                error: function() {
                    console.log(question.OPCIONB);
                    alert("Error al cargar los datos de la pregunta.");
                }
            });
        }


        // Cerrar el modal al hacer clic en la "X"
        document.querySelector(".close").onclick = function() {
            var modal = document.getElementById("editModal");
            modal.style.display = "none";
        };

        // Cerrar el modal al hacer clic fuera del contenido del modal
        window.onclick = function(event) {
            var modal = document.getElementById("editModal");
            if (event.target === modal) {
                modal.style.display = "none";
            }
        };

        // Manejar la presentación del formulario
        document.getElementById("editQuestionForm").onsubmit = function(event) {
            event.preventDefault(); // Prevenir el envío del formulario por defecto
            var questionId = document.getElementById("questionId").value;
            var questionText = document.getElementById("questionText").value;

            // Aquí puedes agregar la lógica AJAX para enviar los datos al servidor
            console.log("ID de la pregunta:", questionId);
            console.log("Nuevo enunciado:", questionText);

            // Cerrar el modal después de guardar
            var modal = document.getElementById("editModal");
            modal.style.display = "none";
        };
    </script>
    <script>
        // Manejar la presentación del formulario
        document.getElementById("editQuestionForm").onsubmit = function(event) {
            event.preventDefault(); // Prevenir el envío del formulario por defecto

            // Recopilar los datos del formulario
            var formData = {

                courseType: document.getElementById("courseType").value,
                typequestion: document.querySelector('input[name="typequestion"]:checked').value,
                question: document.getElementById("question").value,
                correctOption: document.getElementById("correctOption").value,
                option2: document.getElementById("option2").value,
                option3: document.getElementById("option3").value,
                option4: document.getElementById("option4").value,
                affirmation: document.getElementById("affirmation").value,
                justification: document.getElementById("justification").value,
                evidence: document.getElementById("evidence").value,
                questionId: document.getElementById("questionId").value // Asegúrate de tener una variable questionId que ya esté definida
            };

            console.log(document.getElementById("questionId").value + " questionId");
            // Enviar los datos a tu controlador mediante AJAX
            $.ajax({
                url: 'http://172.16.20.165/dist/SigautApp/controllers/update_question.php', // URL del controlador PHP
                type: 'POST', // Usamos POST para enviar los datos
                data: formData, // Los datos recopilados del formulario
                dataType: 'json', // Formato esperado de la respuesta
                success: function(response) {
                    if (response.success) {
                        alert("Pregunta actualizada correctamente.");
                        // Cerrar el modal
                        var modal = document.getElementById("editModal");
                        modal.style.display = "none";
                        // Aquí puedes refrescar la tabla o hacer algo más
                        get_questionsall();
                    } else {
                        alert("Error al actualizar la pregunta.");
                    }
                },
                error: function() {
                    alert("Hubo un error al intentar actualizar la pregunta.");
                }
            });
        };
    </script>
    <script src="./public/js/required.js"></script>
</body>
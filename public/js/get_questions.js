var tableInitialized = false; // Variable para controlar si DataTables ha sido inicializado 

function get_questionsall() {
    var userId = document.getElementById("userid").value; // Suponiendo que tienes un campo oculto con el ID del usuario
   // console.log("userId: " + userId);

    // Crear la solicitud AJAX
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "./controllers/get_questions.php", true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

    // Enviar el ID del usuario
    xhr.send("userId=" + encodeURIComponent(userId));

    xhr.onload = function () {
        if (xhr.status === 200) {
            var questions = JSON.parse(xhr.responseText); // Parsear la respuesta JSON
            //console.log(questions);

            // Obtener el cuerpo de la tabla (tbody)
            var tableBody = document.getElementById("questionsTableBody");
            tableBody.innerHTML = ""; // Limpiar el contenido de la tabla

            // Verificar si hay preguntas
            if (questions.length > 0) {
                // Recorrer las preguntas y agregarlas a la tabla
                questions.forEach(function (question) {
                    var row = document.createElement("tr");

                    // Crear celdas de la tabla
                    var programaCell = document.createElement("td");
                    programaCell.textContent = question.PROGRAMA;

                    var semestreCell = document.createElement("td");
                    semestreCell.textContent = question.SEMESTRE;

                    var grupoCell = document.createElement("td");
                    grupoCell.textContent = question.GRUPO;

                    var enunciadoCell = document.createElement("td");
                    enunciadoCell.textContent = question.ENUNCIADO;

                    var tipoPreguntaCell = document.createElement("td");
                    tipoPreguntaCell.textContent = question.TIPOPREGUNTA;
                    var IDPreguntaCell = document.createElement("td");

                    // Crear el botón "Editar"
                    var editButton = document.createElement("button");
                    editButton.setAttribute("type", "button"); // Definir el tipo del botón
                    editButton.className = "btn-edit"; // Clase CSS para los estilos
                    
                    // Estructura del botón con SVG y texto "Editar"
                    editButton.innerHTML = `
                      <div class="svg-wrapper-1">
                        <div class="svg-wrapper">
                          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="15" height="15" class="icon">
                            <path <path xmlns="http://www.w3.org/2000/svg" d="M20,16v4a2,2,0,0,1-2,2H4a2,2,0,0,1-2-2V6A2,2,0,0,1,4,4H8" fill="none" stroke="#ffffff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></path>
                         <polygon xmlns="http://www.w3.org/2000/svg" fill="none" points="12.5 15.8 22 6.2 17.8 2 8.3 11.5 8 16 12.5 15.8" stroke="#ffffff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></polygon>
                            </svg>
                        </div>
                      </div>
                      <span>Editar</span>
                    `;
                    
                    // Añadir evento click al botón de editar
                    console.log("id"+question['ID']);
                    editButton.onclick = function() {
                        showEditModal(question['ID']); // Llama a la función para mostrar el modal
                    };
                    

                    // Agregar el botón a la celda
                    IDPreguntaCell.appendChild(editButton);

                    // Agregar celdas a la fila
                    row.appendChild(programaCell);
                    row.appendChild(semestreCell);
                    row.appendChild(grupoCell);
                    row.appendChild(enunciadoCell);
                    row.appendChild(tipoPreguntaCell);
                    row.appendChild(IDPreguntaCell);

                    // Agregar fila a la tabla
                    tableBody.appendChild(row);
                });

                // Inicializar DataTables solo si aún no ha sido inicializado
                if (!tableInitialized) {
                    $('#questionsTable').DataTable({
                        "language": {
                            "url": "//cdn.datatables.net/plug-ins/1.13.5/i18n/es-ES.json" // Traducción al español
                        }
                    });
                    tableInitialized = true; // Marcar que la tabla ha sido inicializada
                } else {
                    // Si ya está inicializada, actualizarla
                    $('#questionsTable').DataTable().clear().rows.add($(tableBody).find('tr')).draw();
                }

            } else {
                // Si no hay preguntas, mostrar un mensaje
                var emptyRow = document.createElement("tr");
                var emptyCell = document.createElement("td");
                emptyCell.setAttribute("colspan", "6"); // Asegúrate de que el colspan coincida con el número total de columnas
                emptyCell.textContent = "No hay preguntas disponibles.";
                emptyRow.appendChild(emptyCell);
                tableBody.appendChild(emptyRow);
            }
        } else {
            console.error("Error al obtener las preguntas:", xhr.statusText);
        }
    };
}

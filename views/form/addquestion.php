<?php
require_once('../../lib/auth_roles.php');
?>
<style>
/* Estilos para el modal */
.modal {
  display: none;
  position: fixed;
  z-index: 999;
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  justify-content: center;
  align-items: center;
}

.modal-content {
  width: 25%;
  height: 12%; /* Reducir altura del modal */
  border-radius: 30px;
  box-sizing: border-box;
  padding: 10px 15px;
  background-color: #ffffff;
  box-shadow: rgba(149, 157, 165, 0.2) 0px 8px 24px;
  position: relative;
  display: flex;
  align-items: center;
  justify-content: space-around;
  gap: 15px;
  overflow: hidden;
}

.icon-container {
  width: 50px; /* Tamaño fijo para mantener relación */
  height: 50px; /* Tamaño fijo para mantener relación */
  display: flex;
  justify-content: center;
  align-items: center;
  background-color: #04e40048;
  border-radius: 50%;
  margin-left: 8px;
}

.icon {
  width: 17px;
  height: 17px;
  color: #269b24;
}

.message-text-container {
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: flex-start;
  flex-grow: 1;
}

.message-text,
.sub-text {
  margin: 0;
  cursor: default;
}

.message-text {
  color: #269b24;
  font-size: 17px;
  font-weight: 700;
}

.sub-text {
  font-size: 14px;
  color: #555;
}

.cross-icon {
  width: 18px;
  height: 18px;
  color: #555;
  cursor: pointer;
}

</style>

<body>
  <div class="container mt-1 form-container">
    <h1 class="inputGroup">Formulario para Enviar Preguntas</h1>

    <div id="alert-container"></div> <!-- Contenedor para las alertas -->
    <div id="modal" class="modal">
      <div class="modal-content">
        

        <div class="icon-container">
          <svg
            xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 512 512"
            stroke-width="0"
            fill="currentColor"
            stroke="currentColor"
            class="icon">
            <path
              d="M256 48a208 208 0 1 1 0 416 208 208 0 1 1 0-416zm0 464A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM369 209c9.4-9.4 9.4-24.6 0-33.9s-24.6-9.4-33.9 0l-111 111-47-47c-9.4-9.4-24.6-9.4-33.9 0s-9.4 24.6 0 33.9l64 64c9.4 9.4 24.6 9.4 33.9 0L369 209z">
            </path>
          </svg>
        </div>
        <div class="message-text-container">
          <p class="message-text">Operación exitosa</p>
          <p class="sub-text">Todo parece estar bien</p>
        </div>

        <svg
          xmlns="http://www.w3.org/2000/svg"
          viewBox="0 0 15 15"
          stroke-width="0"
          fill="none"
          stroke="currentColor"
          class="cross-icon" onclick="closeModal()">
          <path
            fill="currentColor"
            d="M11.7816 4.03157C12.0062 3.80702 12.0062 3.44295 11.7816 3.2184C11.5571 2.99385 11.193 2.99385 10.9685 3.2184L7.50005 6.68682L4.03164 3.2184C3.80708 2.99385 3.44301 2.99385 3.21846 3.2184C2.99391 3.44295 2.99391 3.80702 3.21846 4.03157L6.68688 7.49999L3.21846 10.9684C2.99391 11.193 2.99391 11.557 3.21846 11.7816C3.44301 12.0061 3.80708 12.0061 4.03164 11.7816L7.50005 8.31316L10.9685 11.7816C11.193 12.0061 11.5571 12.0061 11.7816 11.7816C12.0062 11.557 12.0062 11.193 11.7816 10.9684L8.31322 7.49999L11.7816 4.03157Z"
            clip-rule="evenodd" fill-rule="evenodd">
          </path>
        </svg>
      </div>
    </div>


    <form id="questionForm" method="post">
      <div class="inputGroup">
        <input type="text" id="user" name="user" class="form-control <?php echo !empty($usuario) ? 'filled' : ''; ?>" value="<?php echo htmlspecialchars($usuario); ?>" readonly />
        <label for="user" class="active">Usuario:</label>
      </div>

      <input type="hidden" id="userid" name="userid" value="<?php echo $userid; ?>">

      <div class="selectGroup mb-3">
        <select id="program" name="program" required onchange="filterSemesters()">
          <option value="" disabled selected>Selecciona un programa</option>
          <option value="Licenciatura en Educación Infantil,19">Licenciatura en Educación Infantil</option>
          <option value="Licenciatura en Pedagogia Infantil,18">Licenciatura en Pedagogia Infantil</option>
          <option value="Criminalística,6">Criminalística</option>
          <option value="Adminitracion Financiera,16">Administración Financiera</option>
          <option value="Mercadeo,20">Mercadeo</option>
        </select>
      </div>

      <div class="selectGroup mb-3">
        <select id="semester" name="semester" required>
          <option value="" disabled selected>Selecciona un semestre</option>
        </select>
      </div>

      <div class="selectGroup mb-3">
        <select id="course" name="course" required>
          <option value="" disabled selected>Selecciona un curso</option>
        </select>
      </div>

      <div class="selectGroup mb-3">
        <select id="courseType" name="courseType" onchange="toggleFields()" required>
          <option value="" disabled selected>Selecciona el tipo de pregunta</option>
          <option value="Seleccion multiple">Selección múltiple</option>
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
        <textarea id="question" name="question" class="form-control" rows="4" required></textarea>
        <label for="question">Pregunta:</label>
      </div>

      <div id="additionalFields" style="display: none">
        <div class="inputGroup">
          <input type="text" id="correctOption" name="correctOption" required autocomplete="off" class="form-control" />
          <label for="correctOption">Opción Correcta</label>
        </div>

        <div class="inputGroup">
          <input type="text" id="option2" name="option2" autocomplete="off" class="form-control" />
          <label for="option2">Opción 2</label>
        </div>

        <div class="inputGroup">
          <input type="text" id="option3" name="option3" autocomplete="off" class="form-control" />
          <label for="option3">Opción 3</label>
        </div>

        <div class="inputGroup">
          <input type="text" id="option4" name="option4" autocomplete="off" class="form-control" />
          <label for="option4">Opción 4</label>
        </div>

        <div class="inputGroup">
          <textarea id="affirmation" name="affirmation" class="form-control" rows="4" autocomplete="off"></textarea>
          <label for="affirmation">Afirmación</label>
        </div>

        <div class="inputGroup">
          <textarea id="justification" name="justification" class="form-control" rows="4" autocomplete="off"></textarea>
          <label for="justification">Justificación</label>
        </div>

        <div class="inputGroup">
          <textarea id="evidence" name="evidence" class="form-control" rows="4" autocomplete="off"></textarea>
          <label for="evidence">Evidencia</label>
        </div>
      </div>

      <div class="text-center">
        <button type="submit">
          <div class="svg-wrapper-1">
            <div class="svg-wrapper">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="30" height="30" class="icon">
                <path d="M20,2H4C2.89,2 2,2.89 2,4V20C2,21.1 2.89,22 4,22H20C21.1,22 22,21.1 22,20V4C22,2.89 21.1,2 20,2ZM6,4H18V8H6V4ZM12,19C10.34,19 9,17.66 9,16C9,14.34 10.34,13 12,13C13.66,13 15,14.34 15,16C15,17.66 13.66,19 12,19ZM18,14H15V11H18V14Z"></path>
              </svg>
            </div>
          </div>
          <span>Guardar</span>
        </button>
      </div>
    </form>
  </div>
</body>

<script>
  document.getElementById("questionForm").addEventListener("submit", function(e) {
    e.preventDefault(); // Evita el envío estándar del formulario

    const formData = new FormData(this); // Recoge los datos del formulario

    fetch('./controllers/process_form.php', {
        method: 'POST',
        body: formData
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          // Si es exitoso, muestra el modal con el mensaje de éxito
          showModal(`Pregunta agregada correctamente. Preguntas cognitivas restantes: ${8 - data.preguntas_restantesC}, Preguntas metacognitivas restantes: ${2 - data.preguntas_restantesM}`, "success");
          resetFormFields();
        } else {
          // Si hay un error en la respuesta del servidor, muestra el mensaje de error
          showModal("Error al agregar la pregunta. Ya has alcanzado el maximo de preguntas de esta categoria.", "danger");
        }
      })
      .catch(error => {
        console.error('Error:', error);
        // Si hay un error en la solicitud (fallo de red, etc.), muestra el mensaje de error
        showModal("Error en la solicitud. Intente de nuevo más tarde.", "danger");
      });
  });
  function resetFormFields() {
    document.getElementById('courseType').value = ''; // Reinicia el select del tipo de pregunta
    document.getElementById('cognitive').checked = false; // Desmarcar radio button cognitiva
    document.getElementById('metacognitive').checked = false; // Desmarcar radio button metacognitiva
    document.getElementById('question').value = ''; // Limpiar el textarea de pregunta
    document.getElementById('correctOption').value = ''; // Limpiar opción correcta
    document.getElementById('option2').value = ''; // Limpiar opción 2
    document.getElementById('option3').value = ''; // Limpiar opción 3
    document.getElementById('option4').value = ''; // Limpiar opción 4
    document.getElementById('affirmation').value = ''; // Limpiar afirmación
    document.getElementById('justification').value = ''; // Limpiar justificación
    document.getElementById('evidence').value = ''; // Limpiar evidencia
  }
  // Función para mostrar el modal con el mensaje
  function showModal(message, type) {
    const modal = document.getElementById('modal');
    const modalMessage = document.querySelector('.message-text'); // Elemento donde se mostrará el título
    const modalSubText = document.querySelector('.sub-text'); // Elemento para el mensaje principal
    const modalIcon = document.querySelector('.icon-container .icon'); // Ícono dentro del modal
    const iconContainer = document.querySelector('.icon-container'); // Contenedor del ícono

    // Personalizar el mensaje dentro del modal
    if (type === "success") {
        modalMessage.innerHTML = "Operación Exitosa"; // Título del modal
        modalSubText.innerHTML = message; // Mensaje principal
        modalMessage.style.color = "#269b24"; // Color verde para éxito
        modalIcon.innerHTML = `
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="icon" fill="currentColor">
            <path d="M256 48a208 208 0 1 1 0 416 208 208 0 1 1 0-416zm0 464A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM369 209c9.4-9.4 9.4-24.6 0-33.9s-24.6-9.4-33.9 0l-111 111-47-47c-9.4-9.4-24.6-9.4-33.9 0s-9.4 24.6 0 33.9l64 64c9.4 9.4 24.6 9.4 33.9 0L369 209z"></path>
          </svg>`;
        iconContainer.style.backgroundColor = "#04e40048"; // Fondo verde para éxito
        modalIcon.style.color = "#269b24"; // Color del ícono verde
    } else {
        modalMessage.innerHTML = "Error"; // Título del modal en caso de error
        modalSubText.innerHTML = message; // Mensaje de error
        modalMessage.style.color = "#d9534f"; // Color rojo para error
        modalIcon.innerHTML = `
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 15 15" class="icon" fill="currentColor">
            <path fill-rule="evenodd" clip-rule="evenodd" fill="currentColor" d="M11.7816 4.03157C12.0062 3.80702 12.0062 3.44295 11.7816 3.2184C11.5571 2.99385 11.193 2.99385 10.9685 3.2184L7.50005 6.68682L4.03164 3.2184C3.80708 2.99385 3.44301 2.99385 3.21846 3.2184C2.99391 3.44295 2.99391 3.80702 3.21846 4.03157L6.68688 7.49999L3.21846 10.9684C2.99391 11.193 2.99391 11.557 3.21846 11.7816C3.44301 12.0061 3.80708 12.0061 4.03164 11.7816L7.50005 8.31316L10.9685 11.7816C11.193 12.0061 11.5571 12.0061 11.7816 11.7816C12.0062 11.557 12.0062 11.193 11.7816 10.9684L8.31322 7.49999L11.7816 4.03157Z"></path>
          </svg>`;
        iconContainer.style.backgroundColor = "#d9534f48"; // Fondo rojo para error
        modalIcon.style.color = "#d9534f"; // Color del ícono rojo
    }

    modal.style.display = "flex"; // Muestra el modal
}


  // Función para cerrar el modal
  function closeModal() {
    const modal = document.getElementById('modal');
    modal.style.display = "none"; // Oculta el modal
  }

  // Cierra el modal cuando se hace clic fuera de él
  window.onclick = function(event) {
    const modal = document.getElementById('modal');
    if (event.target === modal) {
      closeModal();
    }
  }
</script>


<script src="./public/js/get_courses.js"></script>
<script src="./public/js/required.js"></script>
<script src="./public/js/validator.js"></script>

</html>
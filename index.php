<?php
require_once('../config.php');
require_login(); // Verifica que el usuario esté autenticado
global $USER, $DB;

// Verificar si el usuario tiene el rol adecuado
$context = context_system::instance(); // Contexto global

// Verificar roles en el contexto global (gestor global y administrador del sitio)
$sql_global = "SELECT r.shortname
        FROM {role_assignments} ra
        JOIN {context} c ON c.id = ra.contextid
        JOIN {role} r ON r.id = ra.roleid
        WHERE ra.userid = :userid AND c.contextlevel = :contextlevel";
$params_global = [
  'userid' => $USER->id,
  'contextlevel' => CONTEXT_SYSTEM
];
$global_roles = $DB->get_records_sql($sql_global, $params_global);

// Verificar roles en el contexto de curso (docente, editor, gestor de curso)
$sql_course = "SELECT r.shortname
        FROM {role_assignments} ra
        JOIN {context} c ON c.id = ra.contextid
        JOIN {role} r ON r.id = ra.roleid
        WHERE ra.userid = :userid AND c.contextlevel = :contextlevel";
$params_course = [
  'userid' => $USER->id,
  'contextlevel' => CONTEXT_COURSE
];
$course_roles = $DB->get_records_sql($sql_course, $params_course);

$has_role = false;

// Verificar si tiene rol de gestor global o administrador
foreach ($global_roles as $role) {
  if ($role->shortname == 'manager' || is_siteadmin()) {
    $has_role = true;
    break;
  }
}

// Verificar si tiene rol de docente, editor o gestor en un curso
if (!$has_role) { // Solo si no es gestor global o admin, verificar los roles de curso
  foreach ($course_roles as $role) {
    if ($role->shortname == 'editingteacher' || $role->shortname == 'teacher' || $role->shortname == 'manager') {
      $has_role = true;
      break;
    }
  }
}
// Verificar si el usuario tiene el rol adecuado
if ($has_role || is_siteadmin()) {
  $usuario = $USER->username;  // Obtener el nombre de usuario de Moodle
  $userid = $USER->id;         // Obtener el ID del usuario de Moodle
} else {
  // Redirigir al inicio de sesión de Moodle si no está autenticado
  header('Location: ' . $CFG->wwwroot . '/login/index.php');
  exit();
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>SigautApp Web</title>
  <!-- Agregar Bootstrap CSS -->
  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
    rel="stylesheet" />
  <script src="./js/required.js"></script>
  <link rel="stylesheet" href="./css/checbox.css">
  <link rel="stylesheet" href="./css/input.css">
  <link rel="stylesheet" href="./css/button.css">
  <link rel="stylesheet" href="./css/select.css">


</head>

<body>
  <div class="container mt-1 form-container">
    <h1 class="inputGroup">
      Formulario para Enviar Preguntas
    </h1>
    <form action="./lib/process_form.php" method="post">

      <div class="inputGroup">
        <input
          type="text"
          id="user"
          name="user"
          class="form-control <?php echo !empty($usuario) ? 'filled' : ''; ?>"
          value="<?php echo htmlspecialchars($usuario); ?>"
          readonly />
        <label for="user" class="active">Usuario:</label>
      </div>
      <!-- Campo oculto para almacenar el userID -->
      <input type="hidden" id="userid" name="userid" value="<?php echo $userid; ?>">


      <div class="selectGroup mb-3">

        <select id="program" name="program" required onchange="filterSemesters()">
          <option value="" disabled selected>Selecciona un programa</option>
          <option value="Licenciatura en Educación Infantil,19">Licenciatura en Educación Infantil</option>
          <option value="Licenciatura en Pedagogia Infantil,18">Licenciatura en Pedagogia Infantil</option>
          <option value="Criminalística,6">Criminalística</option>
          <option value="Adminitracion Financiera,16">Adminitracion Financiera</option>
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
          <!-- Las opciones se llenarán dinámicamente con JavaScript -->
        </select>
      </div>

      <div class="selectGroup mb-3">

        <select id="courseType" name="courseType" onchange="toggleFields()" required>
          <option value="" disabled selected>Selecciona el tipo de pregunta</option>
          <option value="Seleccion multiple">Seleccion multiple</option>
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


      <div id="additionalFields" style="display: none">
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

      </div>
      <div class="text-center">
        <button type="submit">
          <div class="svg-wrapper-1">
            <div class="svg-wrapper">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 24 24"
                width="30"
                height="30"
                class="icon">
                <path d="M20,2H4C2.89,2 2,2.89 2,4V20C2,21.1 2.89,22 4,22H20C21.1,22 22,21.1 22,20V4C22,2.89 21.1,2 20,2ZM6,4H18V8H6V4ZM12,19C10.34,19 9,17.66 9,16C9,14.34 10.34,13 12,13C13.66,13 15,14.34 15,16C15,17.66 13.66,19 12,19ZM18,14H15V11H18V14Z"></path>
              </svg>
            </div>
          </div>
          <span>Guardar</span>
        </button>
      </div>



    </form>
  </div>
  <script>
    document.querySelector('form').addEventListener('submit', function(event) {
      const questionTextarea = document.getElementById('question');
      const affirmationTextarea = document.getElementById('affirmation');
      const justificationTextarea = document.getElementById('justification');
      const evidenceTextarea = document.getElementById('evidence');

      // Validar que la pregunta no esté vacía
      if (!questionTextarea.value.trim()) {
        alert('Por favor, ingrese una pregunta.');
        event.preventDefault();
        questionTextarea.focus();
        return;
      }

      // Solo validar los textareas si están visibles
      const additionalFields = document.getElementById('additionalFields');
      if (additionalFields.style.display !== 'none') {
        let valid = true; // Variable para controlar la validez

        // Validar afirmación
        if (!affirmationTextarea.value.trim()) {
          alert('Por favor, ingrese una afirmación.');
          affirmationTextarea.focus();
          valid = false;
        }

        // Validar justificación
        if (!justificationTextarea.value.trim()) {
          alert('Por favor, ingrese una justificación.');
          justificationTextarea.focus();
          valid = false;
        }

        // Validar evidencia
        if (!evidenceTextarea.value.trim()) {
          alert('Por favor, ingrese una evidencia.');
          evidenceTextarea.focus();
          valid = false;
        }

        // Si algún campo es inválido, prevenir el envío
        if (!valid) {
          event.preventDefault();
        }
      }
    });
  </script>



  <!-- Agregar Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="./js/get_semesters.js"></script>
  <script src="./js/get_courses.js"></script>
  <script src="./js/alerts.js"></script>
</body>

</html>
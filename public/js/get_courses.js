function filterCourses() {
  var semesterSelect = document.getElementById("semester");
  var semesterValue = semesterSelect.value;
  // Obtener el userId del campo oculto
  var userId = document.getElementById("userid").value;
  // Separar el valor en nombre y ID
  var parts = semesterValue.split(",");
  var semesterId = parts[1]; // El ID del semestre está en la segunda parte (índice 1)

  // Crear la solicitud AJAX
  var xhr = new XMLHttpRequest();
  xhr.open("POST", "./controllers/get_courses.php", true);
  xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

  // Enviar el ID del semestre
  xhr.send("semesterId=" + encodeURIComponent(semesterId)+ "&userId=" + encodeURIComponent(userId));

  xhr.onload = function () {
    //  console.log("Respuesta del servidor:", xhr.responseText); // Agregado para depuración
    if (xhr.status === 200) {
      try {
        var courses = JSON.parse(xhr.responseText);

        // Limpiar el campo de cursos
        var courseSelect = document.getElementById("course");
        courseSelect.innerHTML =
          '<option value="" disabled selected>Selecciona un curso</option>';

        // Agregar las opciones de curso
        courses.forEach(function (course) {
          var option = document.createElement("option");
          option.value = course.fullname;
          option.textContent = course.fullname;
          courseSelect.appendChild(option);
        });
      } catch (e) {
        console.error("Error al analizar JSON:", e);
      }
    } else {
      console.error("Error al obtener los cursos:", xhr.statusText);
    }
  };
}

// Agregar el evento para el cambio en el semestre
document.getElementById("semester").addEventListener("change", filterCourses);

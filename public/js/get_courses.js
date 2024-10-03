function filterCourses() {
  var semesterSelect = document.getElementById("semester");
  var semesterValue = semesterSelect.value;
  
  // Verificar si el elemento del semestre existe y tiene valor
  console.log("Valor del semestre:", semesterValue);
  
  // Obtener el userId del campo oculto
  var userId = document.getElementById("userid").value;
  console.log("ID de usuario:", userId);

  var parts = semesterValue.split(",");
  var semesterId = parts[1]; 
  
  // Verificar que el ID del semestre sea válido
  console.log("ID del semestre:", semesterId);

  var xhr = new XMLHttpRequest();
  xhr.open("POST", "./controllers/get_courses.php", true);
  xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

  xhr.send("semesterId=" + encodeURIComponent(semesterId)+ "&userId=" + encodeURIComponent(userId));

  xhr.onload = function () {
    if (xhr.status === 200) {
      // Verificar la respuesta del servidor
      console.log("Respuesta del servidor:", xhr.responseText);
      
      try {
        var courses = JSON.parse(xhr.responseText);

        var courseSelect = document.getElementById("course");
        courseSelect.innerHTML = '<option value="" disabled selected>Selecciona un curso</option>';

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

// Agregar el evento de cambio
document.getElementById("semester").addEventListener("change", filterCourses);

function filterSemesters() {
  var programSelect = document.getElementById("program");
  var selectedValue = programSelect.value;

  // Obtener el userId del campo oculto
  var userId = document.getElementById("userid").value;

  // Separar el valor en nombre y ID
  var parts = selectedValue.split(",");
  var programId = parts[1];

  // Crear la solicitud AJAX
  var xhr = new XMLHttpRequest();
  xhr.open("POST", "./lib/get_semesters.php", true);
  xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

  // Enviar el ID del programa y el ID del usuario
  xhr.send("programId=" + encodeURIComponent(programId) + "&userId=" + encodeURIComponent(userId));

  xhr.onload = function() {
      if (xhr.status === 200) {
          var semesters = JSON.parse(xhr.responseText);
          //  console.log("Respuesta del servidor:", xhr.responseText); // Agregado para depuración
          // Limpiar el selector de semestres
          var semesterSelect = document.getElementById("semester");
          semesterSelect.innerHTML = '<option value="" disabled selected>Selecciona un semestre</option>';

          // Agregar las opciones de semestre
          semesters.forEach(function(semester) {
              var option = document.createElement("option");
              option.value = semester.name + "," + semester.id;
              option.textContent = semester.name;
              semesterSelect.appendChild(option);
          });
      } else {
          console.error("Error al obtener los semestres:", xhr.statusText);
      }
  };
}

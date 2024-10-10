document.addEventListener("DOMContentLoaded", function() {
  // Obtener los valores de preguntas restantes desde localStorage
  var preguntasRestantesC = localStorage.getItem('preguntas_restantesC');
  var preguntasRestantesM = localStorage.getItem('preguntas_restantesM');

  // Asegúrate de que las variables no sean nulas y conviértelas en números
  if (preguntasRestantesC !== null && preguntasRestantesM !== null) {
      preguntasRestantesC = 8 - parseInt(preguntasRestantesC, 10);
      preguntasRestantesM = 2 - parseInt(preguntasRestantesM, 10);

      // Variables para almacenar los mensajes de alerta
      var mensaje = "";
      var preguntasCognitivasCompletas = "";
      var preguntasMetacognitivasCompletas = "";

      // Verificar si ya se alcanzó el límite de preguntas metacognitivas
      if (preguntasRestantesM <= 0) {
          preguntasMetacognitivasCompletas = "Ya has completado las preguntas Metacognitivas.\n";
      }

      // Verificar si ya se alcanzó el límite de preguntas cognitivas
      if (preguntasRestantesC <= 0) {
          preguntasCognitivasCompletas = "Ya has completado las preguntas Cognitivas.\n";
      }

      // Crear el mensaje basado en las preguntas restantes
      if (preguntasRestantesC > 0 || preguntasRestantesM > 0) {
          mensaje += 'Te quedan ' + preguntasRestantesC + ' preguntas Cognitivas y ' + preguntasRestantesM + ' preguntas Metacognitivas.\n';
      }

      // Concatenar los mensajes de alerta si se han completado las preguntas
      if (preguntasCognitivasCompletas !== "" || preguntasMetacognitivasCompletas !== "") {
          mensaje += preguntasCognitivasCompletas + preguntasMetacognitivasCompletas;
      }

      // Si se alcanzaron ambos límites
      if (preguntasRestantesC <= 0 && preguntasRestantesM <= 0) {
          mensaje = 'Ya has completado las 10 preguntas para este curso.';
      }

      // Mostrar la alerta final si hay algún mensaje
      if (mensaje !== "") {
          alert(mensaje);
      }

      // Limpiar los valores de localStorage
      localStorage.removeItem('preguntas_restantesC');
      localStorage.removeItem('preguntas_restantesM');
  }
});

document.addEventListener("DOMContentLoaded", function() {
    // Obtener los valores de preguntas restantes desde localStorage y restar
    var preguntasRestantesC = localStorage.getItem('preguntas_restantesC');
    var preguntasRestantesM = localStorage.getItem('preguntas_restantesM');
    // alert('Te quedan ' + preguntasRestantesC + 'mmm' + preguntasRestantesM);
    if (preguntasRestantesC !== null && preguntasRestantesM !== null) {
      preguntasRestantesC = 8 - preguntasRestantesC;
      preguntasRestantesM = 2 - preguntasRestantesM;

      // Si existen los valores, mostrar una alerta con las preguntas restantes

      if (preguntasRestantesC > 0 || preguntasRestantesM > 0) {

        alert('Te quedan ' + preguntasRestantesC + ' preguntas Cognitivas y ' + preguntasRestantesM + ' preguntas Metacognitivas');

      } else if (preguntasRestantesC <= 0 && preguntasRestantesM <= 0) {
        alert('Ya has completado las 10 preguntas para este curso');
      }

      // Limpiar el valor de localStorage para futuras visitas
      localStorage.removeItem('preguntas_restantesC');
      localStorage.removeItem('preguntas_restantesM');
    }
  });